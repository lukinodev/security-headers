<?php defined('BLUDIT') or die('Unauthorized access!');

/** -------------------------------------------------------------------------------
 *  HTTP Security Headers plugin
 *  -------------------------------------------------------------------------------
 *  It simply adds security headers to your site.
 *
 *  @author Lukino
 *  @link https://jen-tak.tk/
 *  @version 1.0
 *  @filesource
 */
class pluginSecurityHeaders extends Plugin {



    // Default CDN servers
    public $cdn_servers = 'www.googletagmanager.com www.google-analytics.com fonts.google.com code.jquery.com cdn.jsdelivr.net';



    /** Init
     *
     * @return void
     */
	public function init()
	{
        // Site settings
        global $site;

		// Fields and default values for the database of this plugin
		$this->dbFields = array
		(
			'X-Content-Type-Options'        => 'nosniff',
			'X-XSS-Protection'              => '1; mode=block',
			'X-DNS-Prefetch-Control'        => 'on',
			'X-Frame-Options'               => 'sameorigin',
			'Access-Control-Request-Method' => '"POST, GET"',
            'Access-Control-Allow-Origin'   => $site->url(),
			'Strict-Transport-Security'     => 'max-age=31536000; includeSubDomains; preload',
			'Referrer-Policy'               => 'strict-origin',
			'Expect-CT'                     => 'enforce, max-age=60',
			'Permissions-Policy'            => "geolocation=();",
			'Content-Security-Policy'       => "default-src 'self';script-src 'self' 'unsafe-inline' " . $this->cdn_servers . ";style-src 'self' 'unsafe-inline';",
			'Content-Language'              => str_replace('_', '-', $site->language() ),
			'X-Powered-by'                  => $site->title(),
			'pluginEnabled'                 => false,
		);
	}



    /** Set headers
     *
     * @return void
     */
	public function beforeSiteLoad()
	{
        // if enabled
        if ( $this->getValue('pluginEnabled') == true ):

            @ini_set('display_errors', false );
            @ini_set("session.cookie_secure", true);
            @ini_set("session.use_only_cookies", true);
            @ini_set("session.use_strict_mode", true);
            @ini_set("session.use_trans_sid", false);
            @error_reporting( 0 );
            @header_remove('X-Powered-By');

            header('Content-Language: ' . html_entity_decode( str_replace('_', '-', $this->getValue('Content-Language') ) ) );
            header('X-Powered-by: ' . html_entity_decode( $this->getValue('X-Powered-by') ) );
            header('X-Content-Type-Options: ' . html_entity_decode( $this->getValue('X-Content-Type-Options') ) );
            header('X-XSS-Protection: ' . html_entity_decode( $this->getValue('X-XSS-Protection') ) );
            header('X-DNS-Prefetch-Control: ' . html_entity_decode( $this->getValue('X-DNS-Prefetch-Control') ) );
            header('X-Frame-Options: ' . html_entity_decode( $this->getValue('X-Frame-Options') ) );
            header('Access-Control-Allow-Origin: ' . html_entity_decode( $this->getValue('Access-Control-Allow-Origin') ) );
            header('Access-Control-Request-Method: ' . html_entity_decode( $this->getValue('Access-Control-Request-Method') ) );
            header('Strict-Transport-Security: '. html_entity_decode( $this->getValue('Strict-Transport-Security') ) );
            header('Referrer-Policy: ' . html_entity_decode( $this->getValue('Referrer-Policy') ) );
            header('Expect-CT: ' . html_entity_decode( $this->getValue('Expect-CT') ) );
            header('Permissions-Policy: ' . html_entity_decode( $this->getValue('Permissions-Policy') ) );
            header("Content-Security-Policy: " . html_entity_decode( $this->getValue('Content-Security-Policy') ) );

        endif;
	}



    /** Plugin settings in administration
     *
     * @return void
     */
    public function form()
	{
		global $L;
        global $site;

		$html  = '';
        $html .= '<div class="card shadow mt-5">';
        $html .= '<h5 class="card-header">' . $L->get('sh-card-info') . '</h5>';
        $html .= '<div class="card-body">';
        $html .= '<p class="card-text">' . $L->get('sh-description') . '</p>';
        $html .= '<a href="https://securityheaders.com/?q=' . $site->url() . '&followRedirects=on" class="btn btn-primary" target="_blank" rel="noopener noreferrer">' . $L->get('sh-check-headers') . '</a>';
        $html .= '<a href="#" data-toggle="modal" data-target="#donateModal" title="Donate BTC" class="btn btn-success mx-3 text-center float-right" style="fill:#fff;">' . $L->get('sh-donate-link') . '<br><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"><path d="M13.953 13.452c0 .892-1.133 1.048-2.786 1.048v-2.086c1.562 0 2.786.081 2.786 1.038zm10.047-8.452v14c0 1.104-.896 2-2 2h-20c-1.104 0-2-.896-2-2v-14c0-1.104.896-2 2-2h20c1.104 0 2 .896 2 2zm-8.25 8.326c0-1.066-.866-1.528-1.491-1.687.515-.186 1.113-.948.872-1.857-.204-.768-.916-1.45-2.714-1.51v-1.272h-.833v1.25h-.417v-1.25h-.833v1.25h-2.084v1.25h.681c.367 0 .569.238.569.585v3.704c0 .357-.211.711-.579.711h-.45l-.208 1.241h2.07v1.259h.833v-1.259h.417v1.259h.833v-1.25c2.214 0 3.334-.972 3.334-2.424zm-2.259-2.784c0-.833-.866-1.042-2.324-1.042v2.083c.921 0 2.324-.065 2.324-1.041z"/></svg></a>';
        $html .= '</div>';
        $html .= '</div>';

        $html .= '<div class="form-group mt-5">';
		$html .= '  <label for="pluginEnabled">' . $L->get('sh-plugin-enable') . '</label>';
        $html .= '  <select id="pluginEnabled" class="form-control" name="pluginEnabled" aria-describedby="pluginEnabledHelp">';
		$html .= '      <option value="true" ' . ( $this->getValue('pluginEnabled') === true ? 'selected' : '' ) . '>' . $L->get('sh-plugin-enable-active') . '</option>';
		$html .= '      <option value="false" ' . ( $this->getValue('pluginEnabled') === false ? 'selected' : '' ) . '>' . $L->get('sh-plugin-enable-notactive') . '</option>';
		$html .= '  </select>';
		$html .= '  <small id="pluginEnabledHelp" class="form-text text-muted">' . $L->get('sh-warning') . '</small>';
        $html .= '</div>';

        $html .= '<div class="form-group mt-5">';
		$html .= '  <label for="Content-Language">' . $L->get('sh-content-language') . '</label>';
		$html .= '  <input class="form-control" id="Content-Language" name="Content-Language" aria-describedby="Content-LanguageHelp" type="text" value="' . str_replace('_', '-', $this->getValue('Content-Language') ) . '"  placeholder="' . $L->get('sh-content-language-placeholder') . '" />';
		$html .= '  <small id="Content-LanguageHelp" class="form-text text-muted">' . $L->get('sh-content-language-help') . '</small>';
        $html .= '</div>';

        $html .= '<div class="form-group mt-5">';
		$html .= '  <label for="X-Powered-by">' . $L->get('sh-x-powered-by') . '</label>';
		$html .= '  <input class="form-control" id="X-Powered-by" name="X-Powered-by" aria-describedby="X-Powered-byHelp" type="text" value="' . $this->getValue('X-Powered-by') . '"  placeholder="' . $L->get('sh-x-powered-by-placeholder') . '" />';
		$html .= '  <small id="X-Powered-byHelp" class="form-text text-muted">' . $L->get('sh-x-powered-by-help') . '</small>';
        $html .= '</div>';

        $html .= '<div class="form-group mt-5">';
		$html .= '  <label for="X-Content-Type-Options">' . $L->get('sh-x-content-type-options') . '</label>';
		$html .= '  <input class="form-control" id="X-Content-Type-Options" name="X-Content-Type-Options" aria-describedby="X-Content-Type-OptionsHelp" type="text" value="' . $this->getValue('X-Content-Type-Options') . '"  placeholder="' . $L->get('sh-x-content-type-options-placeholder') . '" />';
		$html .= '  <small id="X-Content-Type-OptionsHelp" class="form-text text-muted">' . $L->get('sh-x-content-type-options-help') . '</small>';
        $html .= '</div>';

        $html .= '<div class="form-group mt-5">';
		$html .= '  <label for="X-XSS-Protection">' . $L->get('sh-x-xss-protection') . '</label>';
		$html .= '  <input class="form-control" id="X-XSS-Protection" name="X-XSS-Protection" aria-describedby="X-XSS-ProtectionHelp" type="text" value="' . $this->getValue('X-XSS-Protection') . '"  placeholder="' . $L->get('sh-x-xss-protection-placeholder') . '" />';
		$html .= '  <small id="X-XSS-ProtectionHelp" class="form-text text-muted">' . $L->get('sh-x-xss-protection-help') . '</small>';
        $html .= '</div>';

        $html .= '<div class="form-group mt-5">';
		$html .= '  <label for="X-DNS-Prefetch-Control">' . $L->get('sh-x-dns-prefetch-control') . '</label>';
		$html .= '  <input class="form-control" id="X-DNS-Prefetch-Control" name="X-DNS-Prefetch-Control" aria-describedby="X-DNS-Prefetch-ControlHelp" type="text" value="' . $this->getValue('X-DNS-Prefetch-Control') . '"  placeholder="' . $L->get('sh-x-dns-prefetch-control-placeholder') . '" />';
		$html .= '  <small id="X-DNS-Prefetch-ControlHelp" class="form-text text-muted">' . $L->get('sh-x-dns-prefetch-control-help') . '</small>';
        $html .= '</div>';

        $html .= '<div class="form-group mt-5">';
		$html .= '  <label for="X-Frame-Options">' . $L->get('sh-x-frame-options') . '</label>';
		$html .= '  <input class="form-control" id="X-Frame-Options" name="X-Frame-Options" aria-describedby="X-Frame-OptionsHelp" type="text" value="' . $this->getValue('X-Frame-Options') . '"  placeholder="' . $L->get('sh-x-frame-options-placeholder') . '" />';
		$html .= '  <small id="X-Frame-OptionsHelp" class="form-text text-muted">' . $L->get('sh-warning') . ' ' . $L->get('sh-x-frame-options-help') . '</small>';
        $html .= '</div>';

        $html .= '<div class="form-group mt-5">';
		$html .= '  <label for="Access-Control-Allow-Origin">' . $L->get('sh-access-controll-allow-origin') . '</label>';
		$html .= '  <input class="form-control" id="Access-Control-Allow-Origin" name="Access-Control-Allow-Origin" aria-describedby="Access-Control-Allow-OriginHelp" type="text" value="' . $this->getValue('Access-Control-Allow-Origin') . '"  placeholder="' . $L->get('sh-access-controll-allow-origin-placeholder') . '" />';
		$html .= '  <small id="Access-Control-Allow-OriginHelp" class="form-text text-muted">' . $L->get('sh-warning') . ' ' . $L->get('sh-access-controll-allow-origin-help') . '</small>';
        $html .= '</div>';

        $html .= '<div class="form-group mt-5">';
		$html .= '  <label for="Access-Control-Request-Method">' . $L->get('sh-access-controll-request-method') . '</label>';
		$html .= '  <input class="form-control" id="Access-Control-Request-Method" name="Access-Control-Request-Method" aria-describedby="Access-Control-Request-MethodHelp" type="text" value="' . $this->getValue('Access-Control-Request-Method')  . '"  placeholder="' . $L->get('sh-access-controll-request-method-placeholder')  . '" />';
		$html .= '  <small id="Access-Control-Request-MethodHelp" class="form-text text-muted">' . $L->get('sh-warning') . ' ' . $L->get('sh-access-controll-request-method-help') . '</small>';
        $html .= '</div>';

        $html .= '<div class="form-group mt-5">';
		$html .= '  <label for="Strict-Transport-Security">' . $L->get('sh-strict-transport-security') . '</label>';
		$html .= '  <input class="form-control" id="Strict-Transport-Security" name="Strict-Transport-Security" aria-describedby="Strict-Transport-SecurityHelp" type="text" value="' . $this->getValue('Strict-Transport-Security') . '"  placeholder="' . $L->get('sh-strict-transport-security-placeholder') . '" />';
		$html .= '  <small id="Strict-Transport-SecurityHelp" class="form-text text-muted">' . $L->get('sh-warning') . ' ' . $L->get('sh-strict-transport-security-help') . '</small>';
        $html .= '</div>';

        $html .= '<div class="form-group mt-5">';
		$html .= '  <label for="Referrer-Policy">' . $L->get('sh-referrer-policy') . '</label>';
		$html .= '  <input class="form-control" id="Referrer-Policy" name="Referrer-Policy" aria-describedby="Referrer-PolicyHelp" type="text" value="' . $this->getValue('Referrer-Policy') . '"  placeholder="' . $L->get('sh-referrer-policy-placeholder') . '" />';
		$html .= '  <small id="Referrer-PolicyHelp" class="form-text text-muted">' . $L->get('sh-referrer-policy-help') . '</small>';
        $html .= '</div>';

        $html .= '<div class="form-group mt-5">';
		$html .= '  <label for="Content-Security-Policy">' . $L->get('sh-csp') . '</label>';
		$html .= '  <input class="form-control" id="Content-Security-Policy" name="Content-Security-Policy" aria-describedby="Content-Security-PolicyHelp" type="text" value="' . $this->getValue('Content-Security-Policy') . '"  placeholder="' . $L->get('sh-csp-placeholder') . '" />';
		$html .= '  <small id="Content-Security-PolicyHelp" class="form-text text-muted">' . $L->get('sh-warning') . ' ' . $L->get('sh-csp-help') . '</small>';
        $html .= '</div>';

        $html .= '<div class="form-group mt-5">';
		$html .= '  <label for="Permissions-Policy">' . $L->get('sh-permissions-policy') . '</label>';
		$html .= '  <input class="form-control" id="Permissions-Policy" name="Permissions-Policy" aria-describedby="Permissions-PolicyHelp" type="text" value="' . $this->getValue('Permissions-Policy') . '"  placeholder="' . $L->get('sh-permissions-policy-placeholder') . '" />';
		$html .= '  <small id="Permissions-PolicyHelp" class="form-text text-muted">' . $L->get('sh-permissions-policy-help') . '</small>';
        $html .= '</div>';

        $html .= '<div class="form-group mt-5">';
		$html .= '  <label for="Expect-CT">' . $L->get('sh-expect-ct') . '</label>';
		$html .= '  <input class="form-control" id="Expect-CT" name="Expect-CT"  aria-describedby="Expect-CTHelp" type="text" value="' . $this->getValue('Expect-CT') . '"  placeholder="' . $L->get('sh-expect-ct-placeholder') . '" />';
		$html .= '  <small id="Expect-CTHelp" class="form-text text-muted">' . $L->get('sh-expect-ct-help') . '</small>';
        $html .= '</div>';

        $html .= '<p class="clearfix mt-5 mb-5">&nbsp;</p>';
        $html .= '<div class="modal fade" id="donateModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">' . $L->get('sh-donate-title') . '</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body"><p><img src="' . DOMAIN_PLUGINS . 'security-headers/img/qr.png" alt="" class="mx-auto d-block img-fluid" /></p><p><br>' . $L->get('sh-donate-text') . '</p><p>BTC: <code><a href="bitcoin:39xYc2jxrxFMiWgWDw7RYUmyF7vF331QWX">39xYc2jxrxFMiWgWDw7RYUmyF7vF331QWX</a></code></p></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">' . $L->get('sh-close') . '</button></div></div></div></div>';

        // compress output
        return trim( str_replace('    ', '', str_replace("\t", '', str_replace("\r\n", '', $html) ) ) );
    }
}
