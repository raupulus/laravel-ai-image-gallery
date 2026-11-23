<div class="pb-4 mb-4 mt-4">
    <h2>Privacy &amp; GDPR Policy</h2>

    <p>
        This Privacy Policy informs you about how personal data is processed on
        <strong>{{ config('app.name') }}</strong> in accordance with Regulation (EU) 2016/679 (GDPR).
    </p>

    <h3>1. Data Controller</h3>
    <ul>
        <li><strong>Responsible:</strong> Raúl Caro Pastorino (<code>@raupulus</code>)</li>
        <li><strong>Contact Email:</strong> <a href="mailto:public@raupulus.dev">public@raupulus.dev</a></li>
        <li><strong>Website:</strong> <a href="{{ route('home') }}">{{ config('app.url') }}</a></li>
    </ul>

    <h3>2. Data We Process and Purpose</h3>
    <ul>
        <li>
            <strong>Public browsing:</strong> Browsing image collections does not require registration or personal
            data submission. Standard web server access logs (IP address, timestamp, user agent) may be recorded
            temporarily for security, abuse prevention, and operational diagnostics.
        </li>
        <li>
            <strong>Analytics (opt-in only):</strong> If Google Analytics is configured and you explicitly accept
            analytics cookies, aggregated usage metrics are collected solely to understand site traffic.
        </li>
        <li>
            <strong>Administration authentication:</strong> Authorized administrators authenticate with email and
            password solely to manage gallery collections. Public registration is disabled.
        </li>
    </ul>

    <h3>3. Legal Basis</h3>
    <ul>
        <li><strong>Legitimate interest</strong> (Art. 6(1)(f) GDPR): site security, rate limiting, and error monitoring.</li>
        <li><strong>Explicit consent</strong> (Art. 6(1)(a) GDPR): optional analytics cookies (withdraw anytime via the
            <a href="{{ route('page.show', 'cookies') }}">Cookies Policy</a>).</li>
    </ul>

    <h3>4. Data Retention &amp; Third-Party Recipients</h3>
    <p>
        Server security logs are retained only as long as strictly necessary for incident response. No personal data is
        sold or shared with third parties except essential infrastructure providers or Google Analytics when you have
        explicitly granted consent.
    </p>

    <h3>5. Your Rights</h3>
    <p>
        You have the right to access, rectify, erase, restrict, or object to the processing of your personal data, as
        well as the right to data portability and to withdraw consent at any time without affecting prior lawful
        processing. To exercise any of these rights, contact
        <a href="mailto:public@raupulus.dev">public@raupulus.dev</a>.
    </p>
</div>
