<?php
declare(strict_types=1);

/**
 * Site content — the PHP equivalent of src/constants/index.ts.
 * Edit text, links, projects and services here.
 */

$SITE = [
    'name'        => 'Mica Devs',
    'eyebrow'     => 'Full-stack development agency',
    'title'       => 'Mica Devs — Full-stack development agency',
    'description' => 'We design, build and ship fast, accessible web products — from MVPs to production platforms.',
    'meta_description' => 'Mica Devs designs, builds and ships fast, accessible web products — from MVPs to production platforms. React, TypeScript, Node.js and cloud.',
    'email'       => env('CONTACT_EMAIL'),
];

$NAV_LINKS = [
    ['label' => 'Services', 'url' => '#services'],
    ['label' => 'Work',     'url' => '#work'],
    ['label' => 'Process',  'url' => '#process'],
    ['label' => 'Contact',  'url' => '#contact'],
];

/** Entries with an empty `url` are not rendered. `icon` = file name in includes/icons. */
$SOCIAL_LINKS = [
    ['label' => 'Code with Mica', 'url' => 'https://cretamusicsoftware.github.io/codewithmica/', 'icon' => 'git-branch'],
    ['label' => 'GitHub',         'url' => '', 'icon' => 'github'],
    ['label' => 'LinkedIn',       'url' => '', 'icon' => 'linkedin'],
];
$SOCIAL_LINKS = array_values(array_filter($SOCIAL_LINKS, static fn($l) => $l['url'] !== ''));

$PROFILE_TAGS = ['React 19', 'TypeScript', 'Node.js', 'AWS', 'GraphQL'];

$SERVICES = [
    ['icon' => 'code',   'title' => 'Web applications',
     'desc' => 'Dashboards, SaaS products and internal tools built with React, TypeScript and Node.js.'],
    ['icon' => 'globe',  'title' => 'Websites & landing pages',
     'desc' => 'Fast, accessible, SEO-ready sites that load in a blink and turn visitors into customers.'],
    ['icon' => 'server', 'title' => 'APIs & backends',
     'desc' => 'Secure REST and GraphQL services, databases and integrations that scale with you.'],
    ['icon' => 'rocket', 'title' => 'Deployment & DevOps',
     'desc' => 'CI/CD, cloud setup and monitoring, so releases stay boring and uptime stays high.'],
];

/**
 * NOTE: placeholder projects — swap in real ones.
 * Leave `url` empty until a live link or case study exists.
 * tone: primary | secondary | tertiary | neutral
 */
$PROJECTS = [
    ['icon' => 'globe', 'tone' => 'primary', 'title' => 'EcoSphere AI', 'featured' => true, 'url' => '',
     'desc' => 'A comprehensive sustainability tracking platform that uses machine learning to analyze carbon footprints and provide actionable insights for businesses and individuals.',
     'tech' => ['React', 'Node.js', 'AI']],
    ['icon' => 'cpu', 'tone' => 'tertiary', 'title' => 'NeuroFlow', 'featured' => false, 'url' => '',
     'desc' => 'Real-time productivity dashboard that syncs with biometric devices to optimize work schedules based on focus levels and energy patterns.',
     'tech' => ['Next.js', 'Socket.io']],
    ['icon' => 'terminal', 'tone' => 'secondary', 'title' => 'Veritas API', 'featured' => false, 'url' => '',
     'desc' => 'High-performance authentication gateway built for Web3 applications with zero-knowledge proof integration and distributed session management.',
     'tech' => ['Go', 'Redis', 'Docker']],
    ['icon' => 'layers', 'tone' => 'neutral', 'title' => 'CloudSync Pro', 'featured' => false, 'url' => '',
     'desc' => 'Enterprise-grade cloud storage management system that seamlessly integrates AWS, Azure, and GCP for unified data management.',
     'tech' => ['Vue.js', 'Python', 'Kubernetes']],
    ['icon' => 'sparkles', 'tone' => 'secondary', 'title' => 'DataViz Studio', 'featured' => false, 'url' => '',
     'desc' => 'Powerful data visualization toolkit with real-time rendering capabilities for large datasets using WebGL acceleration.',
     'tech' => ['D3.js', 'React', 'WebGL']],
    ['icon' => 'mail', 'tone' => 'tertiary', 'title' => 'SecureChat', 'featured' => true, 'url' => '',
     'desc' => 'Privacy-focused messaging app with military-grade encryption, self-destructing messages, and decentralized architecture.',
     'tech' => ['React Native', 'WebRTC', 'Rust']],
];

$PROCESS_STEPS = [
    ['title' => 'Discover',         'desc' => 'We learn your goals, users and constraints, then agree on scope and a realistic timeline.'],
    ['title' => 'Design',           'desc' => 'Wireframes and a clickable prototype so you can react before a line of code is written.'],
    ['title' => 'Build',            'desc' => 'Short iterations with regular demos. You see working software early and often.'],
    ['title' => 'Launch & support', 'desc' => 'We deploy, monitor and stay on hand after go-live for fixes and the next iteration.'],
];

/** `icon` = file name in includes/icons */
$TECH_STACKS = [
    ['name' => 'React',      'icon' => 'react'],
    ['name' => 'Next.js',    'icon' => 'nextjs'],
    ['name' => 'TypeScript', 'icon' => 'typescript'],
    ['name' => 'Node.js',    'icon' => 'nodejs'],
    ['name' => 'Docker',     'icon' => 'docker'],
    ['name' => 'PostgreSQL', 'icon' => 'postgresql'],
];

$PROJECT_TYPES = ['Web application', 'Website / landing page', 'Backend / API', 'Mobile app', 'Not sure yet'];

$BUDGETS = ['Under $1,000', '$1,000 – $5,000', '$5,000 – $15,000', '$15,000+', 'Not sure yet'];

/** Tailwind classes per project tone (full strings so the CSS build can see them). */
$TONES = [
    'primary'   => 'bg-primary-container text-on-primary-container',
    'secondary' => 'bg-secondary-container text-on-secondary-container',
    'tertiary'  => 'bg-tertiary-container text-on-tertiary-container',
    'neutral'   => 'bg-surface-container-high text-on-surface',
];
