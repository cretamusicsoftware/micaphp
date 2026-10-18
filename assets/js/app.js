/**
 * Small vanilla-JS layer replacing the React islands:
 *  1. Theme toggle (was <ThemeToggle>)
 *  2. Scroll-reveal for bento cards (was Motion's whileInView)
 *  3. Contact form: inline validation + fetch submit (was <ContactForm> + EmailJS)
 */
(function () {
  'use strict';

  /* ---------------------------------------------------------------- 1. Theme */
  var root = document.documentElement;

  function syncThemeLabel(btn) {
    var dark = root.classList.contains('dark');
    btn.setAttribute('aria-label', dark ? 'Switch to light theme' : 'Switch to dark theme');
  }

  document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
    syncThemeLabel(btn);
    btn.addEventListener('click', function () {
      var next = !root.classList.contains('dark');
      root.classList.toggle('dark', next);
      try {
        localStorage.setItem('theme', next ? 'dark' : 'light');
      } catch (e) {
        /* storage can be blocked (private mode) — the toggle still works */
      }
      syncThemeLabel(btn);
    });
  });

  /* ------------------------------------------------------- 2. Scroll reveal */
  var cards = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            io.unobserve(entry.target); // once
          }
        });
      },
      { rootMargin: '0px 0px -40px 0px' }
    );
    cards.forEach(function (el) { io.observe(el); });
  } else {
    cards.forEach(function (el) { el.classList.add('is-visible'); });
  }

  /* -------------------------------------------------------- 3. Contact form */
  var form = document.querySelector('[data-contact-form]');
  if (!form) return;

  var success = document.querySelector('[data-form-success]');
  var alertBox = form.querySelector('[data-form-alert]');
  var alertText = form.querySelector('[data-form-alert-text]');
  var submitBtn = form.querySelector('[data-form-submit]');
  var labelIdle = form.querySelector('[data-label-idle]');
  var labelSending = form.querySelector('[data-label-sending]');

  var MESSAGE_MIN = 20;
  var MESSAGE_MAX = 2000;
  var EMAIL_PATTERN = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

  // Mirrors validate_contact() in includes/bootstrap.php (the server re-checks everything).
  function validate(values) {
    var errors = {};
    if (values.name.trim().length < 2) errors.name = 'Please enter your name.';
    if (!EMAIL_PATTERN.test(values.email.trim())) errors.email = 'Please enter a valid email address.';
    if (!values.projectType) errors.projectType = 'Please choose a project type.';
    var len = values.message.trim().length;
    if (len < MESSAGE_MIN) errors.message = 'Tell us a bit more (at least ' + MESSAGE_MIN + ' characters).';
    else if (len > MESSAGE_MAX) errors.message = 'Please keep it under ' + MESSAGE_MAX + ' characters.';
    return errors;
  }

  function values() {
    var out = {};
    ['name', 'email', 'projectType', 'budget', 'message'].forEach(function (k) {
      out[k] = form.elements[k].value;
    });
    return out;
  }

  function setFieldError(key, message) {
    var field = form.elements[key];
    var node = form.querySelector('[data-error-for="' + key + '"]');
    if (!field || !node) return;
    if (message) {
      node.textContent = message;
      node.hidden = false;
      field.setAttribute('aria-invalid', 'true');
      field.setAttribute('aria-describedby', key + '-error');
    } else {
      node.textContent = '';
      node.hidden = true;
      field.removeAttribute('aria-invalid');
      field.removeAttribute('aria-describedby');
    }
  }

  function showErrors(errors) {
    ['name', 'email', 'projectType', 'message'].forEach(function (k) { setFieldError(k, errors[k]); });
  }

  function showAlert(message) {
    if (!message) { alertBox.hidden = true; return; }
    alertText.textContent = message;
    alertBox.hidden = false;
  }

  function setSending(sending) {
    submitBtn.disabled = sending;
    labelIdle.hidden = sending;
    labelSending.hidden = !sending;
    Array.prototype.forEach.call(form.elements, function (el) {
      if (el !== submitBtn && el.name !== 'csrf') el.disabled = sending;
    });
  }

  // Clear a field's error as soon as the visitor starts fixing it.
  form.addEventListener('input', function (event) {
    var name = event.target && event.target.name;
    if (name) setFieldError(name, '');
  });

  form.addEventListener('submit', function (event) {
    event.preventDefault();
    if (submitBtn.disabled) return;

    // Honeypot: pretend it worked so bots get no signal to adapt to.
    if (form.elements.website.value) { showSuccess(); return; }

    var found = validate(values());
    showErrors(found);
    var first = Object.keys(found)[0];
    if (first) { form.elements[first].focus(); return; }

    showAlert('');
    var data = new FormData(form); // reads values before fields are disabled
    setSending(true);

    var controller = new AbortController();
    var timer = setTimeout(function () { controller.abort(); }, 15000);

    fetch(form.action, {
      method: 'POST',
      body: data,
      headers: { Accept: 'application/json' },
      credentials: 'same-origin',
      signal: controller.signal,
    })
      .then(function (res) {
        return res.json().catch(function () { return {}; }).then(function (json) { return { res: res, json: json }; });
      })
      .then(function (r) {
        if (r.res.ok && r.json.ok) {
          form.reset();
          showErrors({});
          showSuccess();
        } else {
          if (r.json.errors) {
            showErrors(r.json.errors);
            var firstKey = Object.keys(r.json.errors)[0];
            if (firstKey) form.elements[firstKey].focus();
          }
          showAlert(r.json.message || 'Something went wrong while sending your message.');
        }
      })
      .catch(function (err) {
        showAlert(
          err && err.name === 'AbortError'
            ? 'That took too long. Please check your connection and try again.'
            : 'Something went wrong while sending your message.'
        );
      })
      .then(function () {
        clearTimeout(timer);
        setSending(false);
      });
  });

  function showSuccess() {
    form.hidden = true;
    success.hidden = false;
    success.setAttribute('tabindex', '-1');
    success.focus();
  }

  var resetBtn = document.querySelector('[data-form-reset]');
  if (resetBtn) {
    resetBtn.addEventListener('click', function () {
      success.hidden = true;
      form.hidden = false;
      showAlert('');
      form.elements.name.focus();
    });
  }
})();
