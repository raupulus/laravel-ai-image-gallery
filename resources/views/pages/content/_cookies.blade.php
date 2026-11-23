<div class="pb-4 mb-4 mt-4">
    <h2>Cookies &amp; Local Storage Policy</h2>

    <p>
        This Cookies Policy explains how <strong>{{ config('app.name') }}</strong> uses browser cookies and
        local storage technologies in compliance with the EU ePrivacy Directive and GDPR.
    </p>

    <h3>1. Strictly Necessary Technical Cookies</h3>
    <p>
        These cookies are essential for the website to operate securely and cannot be disabled without affecting
        core functionality:
    </p>
    <ul>
        <li>
            <strong>Session &amp; CSRF protection cookies</strong> (<code>{{ config('session.cookie') }}</code>,
            <code>XSRF-TOKEN</code>): preserve session integrity and protect forms against Cross-Site Request Forgery.
        </li>
        <li>
            <strong>Theme &amp; consent preferences</strong> (browser <code>localStorage</code> keys
            <code>theme</code> and <code>cookie_consent</code>): remember your light/dark appearance choice and your
            analytics consent status.
        </li>
    </ul>

    <h3>2. Optional Analytics Cookies (Google Analytics)</h3>
    <p>
        When Google Analytics is enabled on this site, analytics scripts and cookies (such as <code>_ga</code> and
        <code>_ga_*</code>) are <strong>blocked by default</strong> and only loaded if you explicitly click
        <strong>Accept</strong> on the consent banner. They are used exclusively to measure aggregated traffic and
        improve gallery performance.
    </p>

    <h3>3. Embedded Videos (YouTube No-Cookie Mode)</h3>
    <p>
        Collections linked to a video use YouTube's privacy-enhanced domain
        (<code>www.youtube-nocookie.com</code>), which limits tracking cookies unless you interact directly with the
        video player.
    </p>

    <h3>4. Manage or Withdraw Your Consent</h3>
    <p>
        You can accept, reject, or change your analytics cookie preference at any time using the button below, or
        by clearing site data in your browser settings.
    </p>

    <button type="button"
            class="btn btn-primary mt-2"
            onclick="if (typeof window.resetCookieConsent === 'function') { window.resetCookieConsent(); }">
        Manage Cookie Preferences
    </button>
</div>
