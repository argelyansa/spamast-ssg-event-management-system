<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Official public homepage of SPAMAST Supreme Student Government. View upcoming events and announcements.">
    <title>SPAMAST SSG | Home</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --rose: #963B60;
            --rose-dark: #713047;
            --rose-light: #F7EAF0;
            --rose-pale: #FCF8FA;
            --text: #34252D;
            --muted: #766873;
            --border: #E9DDE3;
            --white: #FFFFFF;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 90px;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Arial, sans-serif;
            color: var(--text);
            background: var(--white);
            line-height: 1.6;
        }

        a {
            text-decoration: none;
        }

        /* HEADER */

        .site-header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255, 255, 255, .98);
            border-bottom: 1px solid var(--border);
        }

        .header-inner {
            min-height: 76px;
        }

        .brand {
            color: var(--rose);
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: .2px;
            line-height: 1.2;
        }

        .brand:hover {
            color: var(--rose-dark);
        }

        .brand small {
            display: block;
            margin-top: 5px;
            color: var(--muted);
            font-size: .63rem;
            font-weight: 600;
            letter-spacing: 1.3px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 27px;
        }

        .nav-links a:not(.login-btn) {
            color: #594852;
            font-size: .88rem;
            font-weight: 600;
            transition: color .2s ease;
        }

        .nav-links a:not(.login-btn):hover {
            color: var(--rose);
        }

        .login-btn,
        .hero-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--rose);
            color: var(--white);
            border: 1px solid var(--rose);
            border-radius: 6px;
            padding: 10px 21px;
            font-size: .88rem;
            font-weight: 700;
            transition: background .2s ease, border-color .2s ease;
        }

        .login-btn:hover,
        .hero-btn:hover {
            background: var(--rose-dark);
            border-color: var(--rose-dark);
            color: var(--white);
        }

        /* HERO */

        .hero {
            background: linear-gradient(115deg, #FBF3F6 0%, #F7E8EF 100%);
            border-bottom: 1px solid var(--border);
            padding: 88px 0;
        }

        .hero-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.7fr) minmax(240px, .8fr);
            align-items: center;
            gap: 55px;
        }

        .hero-label,
        .section-kicker {
            color: var(--rose);
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .hero-label {
            display: inline-block;
            margin-bottom: 18px;
        }

        .hero h1 {
            max-width: 720px;
            color: #402D37;
            font-size: clamp(2.4rem, 5vw, 3.65rem);
            line-height: 1.15;
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 21px;
        }

        .hero h1 span {
            color: var(--rose);
        }

        .hero-description {
            max-width: 650px;
            color: #6F5A65;
            font-size: 1rem;
            line-height: 1.85;
            margin-bottom: 27px;
        }

        .hero-note {
            margin-top: 17px;
            color: #806C76;
            font-size: .82rem;
        }

        .hero-panel {
            background: rgba(255, 255, 255, .85);
            border: 1px solid #E8D5DE;
            border-radius: 10px;
            padding: 27px;
            box-shadow: 0 8px 24px rgba(80, 42, 59, .045);
        }

        .hero-panel-title {
            color: #49313D;
            font-size: 1rem;
            font-weight: 750;
            margin-bottom: 18px;
        }

        .hero-panel-item {
            padding: 13px 0;
            border-top: 1px solid #F0E5EA;
        }

        .hero-panel-item strong {
            display: block;
            color: var(--rose);
            font-size: .86rem;
            margin-bottom: 3px;
        }

        .hero-panel-item span {
            color: var(--muted);
            font-size: .82rem;
        }

        /* GENERAL SECTIONS */

        .section-space {
            padding: 76px 0;
        }

        .section-heading {
            color: #402D37;
            font-size: 1.85rem;
            font-weight: 800;
            letter-spacing: -.4px;
            margin: 8px 0 9px;
        }

        .section-subtitle {
            color: var(--muted);
            font-size: .94rem;
            line-height: 1.75;
            margin-bottom: 32px;
        }

        /* EVENT AND ANNOUNCEMENT CARDS */

        .content-card {
            height: 100%;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 9px;
            padding: 26px;
            box-shadow: 0 3px 12px rgba(70, 38, 53, .035);
            transition: box-shadow .2s ease, border-color .2s ease;
        }

        .content-card:hover {
            border-color: #D9B8C8;
            box-shadow: 0 8px 22px rgba(70, 38, 53, .075);
        }

        .event-date {
            display: inline-block;
            background: var(--rose-light);
            color: var(--rose-dark);
            border-radius: 4px;
            padding: 6px 10px;
            font-size: .77rem;
            font-weight: 700;
            margin-bottom: 17px;
        }

        .card-title {
            color: #49313D;
            font-size: 1.1rem;
            font-weight: 750;
            line-height: 1.4;
            margin-bottom: 11px;
        }

        .card-description {
            color: var(--muted);
            font-size: .9rem;
            line-height: 1.8;
            overflow-wrap: anywhere;
        }

        .venue {
            color: var(--muted);
            font-size: .85rem;
            margin-top: 17px;
            padding-top: 13px;
            border-top: 1px solid #F0E7EB;
        }

        .venue strong {
            color: var(--rose);
        }

        .announcement-date {
            color: var(--rose);
            font-size: .79rem;
            font-weight: 700;
            margin-bottom: 13px;
        }

        /* EMPTY STATES */

        .empty-state {
            background: var(--rose-pale);
            border: 1px solid var(--border);
            border-left: 4px solid #C27B9A;
            border-radius: 7px;
            padding: 30px;
            color: var(--muted);
        }

        .empty-state h3 {
            color: #49313D;
            font-size: 1rem;
            font-weight: 750;
        }

        .empty-state p {
            font-size: .88rem;
        }

        /* ANNOUNCEMENTS */

        .announcements-section {
            background: #FCF9FA;
            border-top: 1px solid #F0E6EB;
            border-bottom: 1px solid #F0E6EB;
        }

        /* ABOUT */

        .about-section {
            background: var(--white);
        }

        .about-panel {
            background: #F8EDF2;
            border: 1px solid #EAD5DF;
            border-radius: 9px;
            padding: 43px;
        }

        .about-panel h2 {
            color: var(--rose);
            font-size: 1.8rem;
            font-weight: 800;
            margin: 9px 0 14px;
        }

        .about-panel p {
            max-width: 850px;
            color: #6F5A65;
            font-size: .94rem;
            line-height: 1.9;
            margin-bottom: 0;
        }

        /* FOOTER */

        .site-footer {
            background: var(--rose-dark);
            color: #F6EAF0;
            padding: 32px 0;
            font-size: .84rem;
        }

        .site-footer strong {
            color: var(--white);
            font-size: .9rem;
        }

        .footer-caption {
            color: #E7CCD8;
            font-size: .78rem;
            margin-top: 5px;
        }

        .footer-copyright {
            color: #F0DDE5;
        }

        /* RESPONSIVE */

        @media (max-width: 767px) {
            .header-inner {
                min-height: 68px;
            }

            .brand {
                font-size: 1.02rem;
            }

            .brand small {
                font-size: .56rem;
            }

            .nav-links {
                gap: 10px;
            }

            .nav-links a:not(.login-btn) {
                display: none;
            }

            .login-btn {
                padding: 8px 15px;
                font-size: .82rem;
            }

            .hero {
                padding: 62px 0;
            }

            .hero-layout {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .hero h1 {
                font-size: 2.35rem;
            }

            .hero-description {
                font-size: .94rem;
            }

            .hero-panel {
                padding: 22px;
            }

            .section-space {
                padding: 55px 0;
            }

            .section-heading {
                font-size: 1.6rem;
            }

            .about-panel {
                padding: 28px 23px;
            }

            .about-panel h2 {
                font-size: 1.55rem;
            }

            .empty-state {
                padding: 25px 20px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            *,
            *::before,
            *::after {
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>
<body>

<header class="site-header">
    <div class="container header-inner d-flex align-items-center justify-content-between gap-3">
        <a href="{{ route('home') }}" class="brand">
            SPAMAST SSG
            <small>SUPREME STUDENT GOVERNMENT</small>
        </a>

        <nav class="nav-links" aria-label="Main navigation">
            <a href="#upcoming-events">Events</a>
            <a href="#announcements">Announcements</a>
            <a href="#about">About</a>
            <a href="{{ route('login') }}" class="login-btn">Login</a>
        </nav>
    </div>
</header>

<main>
    <section class="hero">
        <div class="container">
            <div class="hero-layout">
                <div>
                    <span class="hero-label">SPAMAST SUPREME STUDENT GOVERNMENT</span>

                    <h1>
                        Welcome to <span>SPAMAST SSG</span>
                    </h1>

                    <p class="hero-description">
                        Stay informed and connected with your student government.
                        Discover upcoming campus activities, read official announcements,
                        and stay updated on the latest initiatives of the SPAMAST SSG.
                    </p>

                    <a href="#upcoming-events" class="hero-btn">
                        Explore Events &rarr;
                    </a>

                    <div class="hero-note">
                        Your official source for SSG events and public announcements.
                    </div>
                </div>

                <aside class="hero-panel">
                    <h2 class="hero-panel-title">Stay Connected</h2>

                    <div class="hero-panel-item">
                        <strong>Campus Activities</strong>
                        <span>Keep track of upcoming student events and programs.</span>
                    </div>

                    <div class="hero-panel-item">
                        <strong>Official Announcements</strong>
                        <span>Read important updates published by the SSG.</span>
                    </div>

                    <div class="hero-panel-item">
                        <strong>Student Representation</strong>
                        <span>Stay connected with your student government.</span>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <section class="section-space" id="upcoming-events">
        <div class="container">
            <div class="section-kicker">Campus Activities</div>
            <h2 class="section-heading">Upcoming Events</h2>
            <p class="section-subtitle">
                Explore activities and events organized or announced by the SPAMAST SSG.
            </p>

            @if($events->isNotEmpty())
                <div class="row g-4">
                    @foreach($events as $event)
                        <div class="col-md-6 col-lg-4">
                            <article class="content-card">
                                <span class="event-date">
                                    {{ $event->event_date->format('F d, Y') }}
                                </span>

                                <h3 class="card-title">{{ $event->title }}</h3>

                                @if($event->description)
                                    <p class="card-description">
                                        {{ \Illuminate\Support\Str::limit($event->description, 180) }}
                                    </p>
                                @else
                                    <p class="card-description">
                                        More event details will be announced soon.
                                    </p>
                                @endif

                                @if($event->venue)
                                    <div class="venue">
                                        <strong>Venue:</strong> {{ $event->venue }}
                                    </div>
                                @endif
                            </article>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <h3 class="mb-2">No upcoming events yet</h3>
                    <p class="mb-0">
                        Please check back later for new SSG activities and event announcements.
                    </p>
                </div>
            @endif
        </div>
    </section>

    <section class="section-space announcements-section" id="announcements">
        <div class="container">
            <div class="section-kicker">Official Updates</div>
            <h2 class="section-heading">Latest Announcements</h2>
            <p class="section-subtitle">
                Stay informed about the latest news and important information from the SSG.
            </p>

            @if($announcements->isNotEmpty())
                <div class="row g-4">
                    @foreach($announcements as $announcement)
                        <div class="col-md-6">
                            <article class="content-card">
                                <div class="announcement-date">
                                    Published {{ $announcement->created_at->format('F d, Y') }}
                                </div>

                                <h3 class="card-title">{{ $announcement->title }}</h3>

                                <p class="card-description mb-0">
                                    {{ \Illuminate\Support\Str::limit($announcement->body, 250) }}
                                </p>
                            </article>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <h3 class="mb-2">No public announcements yet</h3>
                    <p class="mb-0">
                        New announcements will appear here once they have been published.
                    </p>
                </div>
            @endif
        </div>
    </section>

    <section class="section-space about-section" id="about">
        <div class="container">
            <div class="about-panel">
                <div class="section-kicker">About the Organization</div>
                <h2>About SPAMAST SSG</h2>
                <p>
                    The Supreme Student Government serves as a student representation
                    platform within SPAMAST. This public page provides students and
                    visitors with a convenient place to view SSG activities, upcoming
                    events, and official announcements.
                </p>
            </div>
        </div>
    </section>
</main>

<footer class="site-footer">
    <div class="container d-flex flex-column flex-md-row justify-content-between gap-3">
        <div>
            <strong>SPAMAST SSG</strong>
            <div class="footer-caption">
                Supreme Student Government | Event Management System
            </div>
        </div>

        <div class="footer-copyright align-self-md-center">
            &copy; {{ date('Y') }} SPAMAST SSG. All rights reserved.
        </div>
    </div>
</footer>

</body>
</html>
