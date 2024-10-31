<?php
/*
Plugin Name: sanctions.io search
Description: Search interface for the sanctions.io API. Does not use own data, just queries API and shows stuff
Version: 1.0
Author: sanctions.io team
Author URI: https://www.sanctions.io
License: GPLv2 or later
*/

/* Redirect to help page after plugin activated */
add_action( 'activated_plugin', 'sanctions_activation_redirect' );
function sanctions_activation_redirect( $plugin ) {
    if( $plugin == plugin_basename( __FILE__ ) ) {
        exit( wp_redirect( admin_url( 'admin.php?page=sanctions-help' ) ) );
    }
}

/* Add Sanctions Api Key menu in WordPress admin */
add_action( 'admin_menu', 'sanctions_extra_post_info_menu' );  
function sanctions_extra_post_info_menu(){    
    $page_title = 'Sanctions Search';   
    $menu_title = 'Sanctions Search';   
    $capability = 'manage_options';   
    $menu_slug  = 'sanctions-apikey';   
    $function   = 'sanctions_apikey';   
    $icon_url   = 'dashicons-media-code';   
    $position   = 4;    
    add_menu_page( $page_title, $menu_title, $capability, $menu_slug,$function, $icon_url, $position );
	
	add_submenu_page( 'sanctions-apikey', 'Settings', 'Settings', 'manage_options', 'sanctions-apikey', 'sanctions_apikey' );
	
	add_submenu_page( 'sanctions-apikey', 'Help', 'Help', 'manage_options', 'sanctions-help', 'sanctions_help' );

}

/* Add API Key on WordPress admin */
function sanctions_apikey() {
    $site_url = get_site_url();
	$sanctions_apikey = get_option('sanctions-apikey');
    ?>
		<?php if(isset($_SESSION['suscess']) && $_SESSION['suscess'] == true){ ?>
			<div id="setting-error-settings_updated" class="notice notice-success settings-error is-dismissible"> 
				<p><strong>API key has been saved successfully.</strong></p>
				<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
			</div>
		<?php
		$_SESSION['suscess'] = false;
		} ?>		
		<div class="ss-form-container">
			<div class="ss-form" >                  
				<div class="ss-form-header">                          
					<h2>Sanctions Search <span>Settings</span></h2>            
				</div>
				<form class="ss-form-body" name="ss-form-body" method="post" enctype="multipart/form-data">
					<div class="ss_12">                    
						<div class="ss_set_row">                        
							<label>Enter Your sanctions.io API Key<span>*</span></label>          
							<input type="text" name="sanctions_api_key" class="ss_appkey" value="<?= isset($sanctions_apikey) ? $sanctions_apikey : ""; ?>" required>           
						</div>                
					</div>
					<div class="ss_12">                    
						<div class="ss_set_row submitssdetail">                        
							<button type="submit" name="submit" value="Submit" class="ss-proceed-btn">Submit</button>                    
						</div>                 
					</div> 
				</form>
			</div>
		</div>
    <?php
    if(isset($_POST['submit']) && esc_attr($_POST['submit']) != ""){
        $sanctions_api_key = sanitize_text_field($_POST['sanctions_api_key']);
        if(isset($sanctions_api_key) && $sanctions_api_key != ""){
            update_option('sanctions-apikey', $sanctions_api_key);
			$_SESSION['suscess'] = true;
			echo "<script>location.reload(true);</script>";
        }
    }
}

/* Help page */
function sanctions_help(){
	?>	
	<div class="ss-help">
		<div class="ss-help-header"><h2 class="title">sanctions.io Search <span>Help</span></h2></div>
		<ul class="ss-help-list">
			<li>Place this shortcode in the page, post or text widget where you want to use the search form: <strong class="scss">[sanctions-search]</strong></li>
			<li>Place this shortcode below the search form shortcode: <strong class="scss">[sanctions-search-results]</strong></li>
		</ul>
		<ul class="ss-help-list">
			<li>Place this shortcode in any template parts of your theme:</li>
			<ul>
                <li>do_shortcode('[sanctions-search]');</li>
                <li>do_shortcode('[sanctions-search-results]');</li>
            </ul>
		</ul>
	</div>
	<?php
}

/* Class declaration */
class SanctionsSearch {
    const COUNTRY_LIST = [
        "AF" => "Afghanistan",
        "AX" => "Åland Islands",
        "AL" => "Albania",
        "DZ" => "Algeria",
        "AS" => "American Samoa",
        "AD" => "Andorra",
        "AO" => "Angola",
        "AI" => "Anguilla",
        "AQ" => "Antarctica",
        "AG" => "Antigua and Barbuda",
        "AR" => "Argentina",
        "AM" => "Armenia",
        "AW" => "Aruba",
        "AU" => "Australia",
        "AT" => "Austria",
        "AZ" => "Azerbaijan",
        "BS" => "Bahamas",
        "BH" => "Bahrain",
        "BD" => "Bangladesh",
        "BB" => "Barbados",
        "BY" => "Belarus",
        "BE" => "Belgium",
        "BZ" => "Belize",
        "BJ" => "Benin",
        "BM" => "Bermuda",
        "BT" => "Bhutan",
        "BO" => "Bolivia",
        "BA" => "Bosnia and Herzegovina",
        "BW" => "Botswana",
        "BV" => "Bouvet Island",
        "BR" => "Brazil",
        "IO" => "British Indian Ocean Territory",
        "BN" => "Brunei Darussalam",
        "BG" => "Bulgaria",
        "BF" => "Burkina Faso",
        "BI" => "Burundi",
        "KH" => "Cambodia",
        "CM" => "Cameroon",
        "CA" => "Canada",
        "CV" => "Cape Verde",
        "KY" => "Cayman Islands",
        "CF" => "Central African Republic",
        "TD" => "Chad",
        "CL" => "Chile",
        "CN" => "China",
        "CX" => "Christmas Island",
        "CC" => "Cocos (Keeling) Islands",
        "CO" => "Colombia",
        "KM" => "Comoros",
        "CG" => "Congo",
        "CD" => "Congo, The Democratic Republic of The",
        "CK" => "Cook Islands",
        "CR" => "Costa Rica",
        "CI" => "Cote D'ivoire",
        "HR" => "Croatia",
        "CU" => "Cuba",
        "CY" => "Cyprus",
        "CZ" => "Czech Republic",
        "DK" => "Denmark",
        "DJ" => "Djibouti",
        "DM" => "Dominica",
        "DO" => "Dominican Republic",
        "EC" => "Ecuador",
        "EG" => "Egypt",
        "SV" => "El Salvador",
        "GQ" => "Equatorial Guinea",
        "ER" => "Eritrea",
        "EE" => "Estonia",
        "ET" => "Ethiopia",
        "FK" => "Falkland Islands (Malvinas)",
        "FO" => "Faroe Islands",
        "FJ" => "Fiji",
        "FI" => "Finland",
        "FR" => "France",
        "GF" => "French Guiana",
        "PF" => "French Polynesia",
        "TF" => "French Southern Territories",
        "GA" => "Gabon",
        "GM" => "Gambia",
        "GE" => "Georgia",
        "DE" => "Germany",
        "GH" => "Ghana",
        "GI" => "Gibraltar",
        "GR" => "Greece",
        "GL" => "Greenland",
        "GD" => "Grenada",
        "GP" => "Guadeloupe",
        "GU" => "Guam",
        "GT" => "Guatemala",
        "GG" => "Guernsey",
        "GN" => "Guinea",
        "GW" => "Guinea-bissau",
        "GY" => "Guyana",
        "HT" => "Haiti",
        "HM" => "Heard Island and Mcdonald Islands",
        "VA" => "Holy See (Vatican City State)",
        "HN" => "Honduras",
        "HK" => "Hong Kong",
        "HU" => "Hungary",
        "IS" => "Iceland",
        "IN" => "India",
        "ID" => "Indonesia",
        "IR" => "Iran, Islamic Republic of",
        "IQ" => "Iraq",
        "IE" => "Ireland",
        "IM" => "Isle of Man",
        "IL" => "Israel",
        "IT" => "Italy",
        "JM" => "Jamaica",
        "JP" => "Japan",
        "JE" => "Jersey",
        "JO" => "Jordan",
        "KZ" => "Kazakhstan",
        "KE" => "Kenya",
        "KI" => "Kiribati",
        "KP" => "Korea, Democratic People's Republic of",
        "KR" => "Korea, Republic of",
        "KW" => "Kuwait",
        "KG" => "Kyrgyzstan",
        "LA" => "Lao People's Democratic Republic",
        "LV" => "Latvia",
        "LB" => "Lebanon",
        "LS" => "Lesotho",
        "LR" => "Liberia",
        "LY" => "Libyan Arab Jamahiriya",
        "LI" => "Liechtenstein",
        "LT" => "Lithuania",
        "LU" => "Luxembourg",
        "MO" => "Macao",
        "MK" => "Macedonia, The Former Yugoslav Republic of",
        "MG" => "Madagascar",
        "MW" => "Malawi",
        "MY" => "Malaysia",
        "MV" => "Maldives",
        "ML" => "Mali",
        "MT" => "Malta",
        "MH" => "Marshall Islands",
        "MQ" => "Martinique",
        "MR" => "Mauritania",
        "MU" => "Mauritius",
        "YT" => "Mayotte",
        "MX" => "Mexico",
        "FM" => "Micronesia, Federated States of",
        "MD" => "Moldova, Republic of",
        "MC" => "Monaco",
        "MN" => "Mongolia",
        "ME" => "Montenegro",
        "MS" => "Montserrat",
        "MA" => "Morocco",
        "MZ" => "Mozambique",
        "MM" => "Myanmar",
        "NA" => "Namibia",
        "NR" => "Nauru",
        "NP" => "Nepal",
        "NL" => "Netherlands",
        "AN" => "Netherlands Antilles",
        "NC" => "New Caledonia",
        "NZ" => "New Zealand",
        "NI" => "Nicaragua",
        "NE" => "Niger",
        "NG" => "Nigeria",
        "NU" => "Niue",
        "NF" => "Norfolk Island",
        "MP" => "Northern Mariana Islands",
        "NO" => "Norway",
        "OM" => "Oman",
        "PK" => "Pakistan",
        "PW" => "Palau",
        "PS" => "Palestinian Territory, Occupied",
        "PA" => "Panama",
        "PG" => "Papua New Guinea",
        "PY" => "Paraguay",
        "PE" => "Peru",
        "PH" => "Philippines",
        "PN" => "Pitcairn",
        "PL" => "Poland",
        "PT" => "Portugal",
        "PR" => "Puerto Rico",
        "QA" => "Qatar",
        "RE" => "Reunion",
        "RO" => "Romania",
        "RU" => "Russian Federation",
        "RW" => "Rwanda",
        "SH" => "Saint Helena",
        "KN" => "Saint Kitts and Nevis",
        "LC" => "Saint Lucia",
        "PM" => "Saint Pierre and Miquelon",
        "VC" => "Saint Vincent and The Grenadines",
        "WS" => "Samoa",
        "SM" => "San Marino",
        "ST" => "Sao Tome and Principe",
        "SA" => "Saudi Arabia",
        "SN" => "Senegal",
        "RS" => "Serbia",
        "SC" => "Seychelles",
        "SL" => "Sierra Leone",
        "SG" => "Singapore",
        "SK" => "Slovakia",
        "SI" => "Slovenia",
        "SB" => "Solomon Islands",
        "SO" => "Somalia",
        "ZA" => "South Africa",
        "GS" => "South Georgia and The South Sandwich Islands",
        "ES" => "Spain",
        "LK" => "Sri Lanka",
        "SD" => "Sudan",
        "SR" => "Suriname",
        "SJ" => "Svalbard and Jan Mayen",
        "SZ" => "Swaziland",
        "SE" => "Sweden",
        "CH" => "Switzerland",
        "SY" => "Syrian Arab Republic",
        "TW" => "Taiwan, Province of China",
        "TJ" => "Tajikistan",
        "TZ" => "Tanzania, United Republic of",
        "TH" => "Thailand",
        "TL" => "Timor-leste",
        "TG" => "Togo",
        "TK" => "Tokelau",
        "TO" => "Tonga",
        "TT" => "Trinidad and Tobago",
        "TN" => "Tunisia",
        "TR" => "Turkey",
        "TM" => "Turkmenistan",
        "TC" => "Turks and Caicos Islands",
        "TV" => "Tuvalu",
        "UG" => "Uganda",
        "UA" => "Ukraine",
        "AE" => "United Arab Emirates",
        "GB" => "United Kingdom",
        "US" => "United States",
        "UM" => "United States Minor Outlying Islands",
        "UY" => "Uruguay",
        "UZ" => "Uzbekistan",
        "VU" => "Vanuatu",
        "VE" => "Venezuela",
        "VN" => "Viet Nam",
        "VG" => "Virgin Islands, British",
        "VI" => "Virgin Islands, U.S.",
        "WF" => "Wallis and Futuna",
        "EH" => "Western Sahara",
        "YE" => "Yemen",
        "ZM" => "Zambia",
        "ZW" => "Zimbabwe"
    ];

    const PAGE_SIZE = 25;

    /**
     * Shortcode to add the search-mask to a page/post
     *
     * @param string $actionTarget URL where the search-form is pointing to. Should be a page including the result-shortcode
     * @return string Content
     */
    public function searchPage($actionTarget = '#') {
        ob_start();

        $more = false;

        // Check if we used advanced search mode to show all params on load
        foreach(['saddress', 'scountry', 'slist', 'smode'] as $key) {
            if(isset($_GET[$key]) && !empty($_GET[$key])) {
                $key=filter_var($_GET[$key], FILTER_SANITIZE_STRING); 
                $more = true;
            }
        }

        $source_list = [];
        $sanctions_apikey = get_option('sanctions-apikey');
        if(isset($sanctions_apikey) && $sanctions_apikey != ""){
			if(isset($this->queryAPIData('sources')->results)){
				foreach($this->queryAPIData('sources')->results as $source) {
					$source_list[$source->short_name] = $source->name;
				}
			}
        }
        // Search mask HTML
        ?>
        <div id="sanctions-container">
            <form id="sanctions-search-form" action="" name="search-form" method="get" action="<?=$actionTarget?>" style="margin: 50px auto;">
                <p class="error">This field is required!</p>
                <input class="input-text" type="text" name="sname" placeholder="Name.." id="id_sname" value="<?=isset($_GET['sname']) ? esc_attr($_GET['sname']) : ''?>" />
                <div id="search-additional"<?=$more ? ' style="display: block;"' : ''?>>

                    <div>
                    <input type="text" name="saddress" placeholder="Address.." value="<?=isset($_GET['saddress']) ? esc_attr($_GET['saddress']) : ''?>" />
                    </div>
                    <div>
                    <select name="scountry[]" title="Country" id="id_scountry" multiple="multiple" class="">
                        <?php foreach(self::COUNTRY_LIST as $key => $name): ?>
                        <option value="<?=$key?>"<?=(isset($_GET['scountry']) && array_search($key, $_GET['scountry']) !== FALSE) ? ' selected="selected"' : ''?>><?=$name?></option>
                        <?php endforeach; ?>
                    </select>
                    </div>
                 
                    <div>
                    <select name="slist[]" title="Lists" multiple="multiple" id="id_slist">
                        <?php foreach($source_list as $key => $name): ?>
                        <option value="<?=$key?>"<?=(isset($_GET['slist']) && array_search($key, $_GET['slist']) !== FALSE) ? ' selected="selected"' : ''?>><?=$name ? $name : $key?></option>
                        <?php endforeach; ?>
                    </select>
                    </div>
                    <div>
                        <input type="checkbox" name="smode" value="fuzzy" id="id_smode"<?php if(isset($_GET['smode']) && $_GET['smode'] == 'fuzzy'){echo 'checked="checked"';}?>>
                        <label style="color:#000" for="id_smode">Use Fuzzy search for name value</label>
                    </div>

                    <div>
                        <input type="number" name="sfuzziness" min="10" max="100" id="id_fuzziness" value="<?=isset($_GET['sfuzziness']) ? intval($_GET['sfuzziness']) : 85 ?>" />
                        <label for="id_fuzziness">Fuzziness level</label>
                    </div>
                </div>
            
                <input type="submit" value="Search">
                <div>
                    <span class="more">
                        <?php if($more): ?>
                        <a href="#more" data-toggletext="More options">Less options</a>
                        <?php else: ?>
                        <a href="#more" data-toggletext="Less options">More options</a>
                        <?php endif; ?>
                    </span>
                </div>
            </form>
            <!-- <?php if(is_front_page()) : ?>
                <a href="#content" class="scroll fa fa-angle-down icon-arrow-down" id="home-info-scroll"></a>
            <?php endif; ?> -->
        </div>

        <script type="text/javascript">
            (function($) {
                var $containerFull = $('#sanctions-container-full');
                $('body').prepend($containerFull);
                <?php if(is_front_page()): ?>
                $containerFull.append($('#home-info-scroll'));
                <?php endif; ?>

                var $moreOptions = $('a[href="#more"]');
                var $searchAdditional = $('#search-additional');
                var $searchForm = $('#sanctions-search-form');

                var $selectCountry = $('#id_scountry');
                var $selectLists = $('#id_slist');


                $(function() {
                    $selectCountry.select2({placeholder: 'Country..'});
                    $selectLists.select2({placeholder: 'Lists..'});

                    $('a.scroll').click(function(e) {
                        e.preventDefault();
                        var $self = $(this);

                        $('html, body').animate({
                            scrollTop: $($self.attr('href')).offset().top
                        }, 1500);
                    });

                    $searchForm.submit(function() {
                        var $nameField = $('#id_sname');

                        if($nameField.val().trim() == '') {
                            $nameField.prev().show();
                            $nameField.addClass('error');
                            return false;
                        } else {
                            $nameField.prev().hide();
                            $nameField.removeClass('error');
                        }

                        
                    });
                });

                $moreOptions.click(function(e) {
                    e.preventDefault();
                    $searchAdditional.slideToggle();

                    var tt = $(this).data('toggletext');
                    $(this).data('toggletext', $(this).text());
                    $(this).text(tt);
                });
            })(jQuery);
        </script>

        <?php
        $content = ob_get_clean();
        return $content;
    }

    /**
     * Shortcode to show our search results and actually trigger the search
     *
     * @return string Content
     */
    public function renderResults() {

        // Check if name is set, only then trigger search
        if(isset($_GET['sname']) && !empty($_GET['sname'])) {
            $params = $this->extractParams();
      
            // do search
            $sfuzziness = '';
            if(isset($params['sfuzziness'])){
                $sfuzziness = (int)$params['sfuzziness'];
            }
            $result = $this->queryAPI($params['sname'], $params['scountry'], $params['saddress'], $params['slist'], $params['smode'], $sfuzziness, (int)$params['spage']);
        }
        ob_start();
        // Result HTML
        ?>

        <?php if(isset($result)): ?>
            <?php if($result->count > 0): ?>
                <div class="clearfix">
                    <p class="pull-right"><?=$result->count?> Entries found.</p>
                </div>
                <ol id="sanctions-search-result-list">
                    <?=$this->renderResultList($result->results)?>
                </ol>
                <div class="sk-fading-circle" id="sanctions-search-loading-spinner">
                    <div class="sk-circle1 sk-circle"></div>
                    <div class="sk-circle2 sk-circle"></div>
                    <div class="sk-circle3 sk-circle"></div>
                    <div class="sk-circle4 sk-circle"></div>
                    <div class="sk-circle5 sk-circle"></div>
                    <div class="sk-circle6 sk-circle"></div>
                    <div class="sk-circle7 sk-circle"></div>
                    <div class="sk-circle8 sk-circle"></div>
                    <div class="sk-circle9 sk-circle"></div>
                    <div class="sk-circle10 sk-circle"></div>
                    <div class="sk-circle11 sk-circle"></div>
                    <div class="sk-circle12 sk-circle"></div>
                </div>
            <?php else: ?>
                <p>No Results found.</p>
            <?php endif; ?>
        <?php endif; ?>

        <script type="text/javascript">

            if (window.location.search.indexOf('sname') > -1) {
                jQuery('html, body').animate({
                    scrollTop: jQuery("#sanctions-search-result-list").offset().top - 100
                }, 200);
            }

            (function($) {
                var currentPage = 1;
                var loading = false;

                $(function() {
                    var $loadingSpinner = $('#sanctions-search-loading-spinner');

                    $("#sanctions-search-result-list").on('click', '> li', function(e) {
                        e.preventDefault();
                        $(this).toggleClass('active');
                        $('.sanctions-search-results-additional', $(this)).slideToggle();
                    });

                    $(window).scroll(function() {
                        if(!loading && (($(document).height() - ($(document).scrollTop() + $(window).height())) < ($(window).height() * 0.8))) {
                            loading = true;
                            $loadingSpinner.show();

                            $.ajax({
                                url: '<?=admin_url('admin-ajax.php')?>',
                                data: window.location.search.substr(1).replace(/(&spage=\d+|$)/, '&spage=' + (currentPage + 1)) + '&action=sanctions_load_page',
                                method: 'POST'
                            }).success(function(data) {
                                data = JSON.parse(data);
                                if(data.success) {
                                    $('ol#sanctions-search-result-list').append(data.resultList);
                                    currentPage++;
                                    loading = false;
                                }
                                $loadingSpinner.hide();
                            });
                        }
                    });
                });
            })(jQuery);
        </script>

        <?php
        $content = ob_get_clean();
        return $content;
    }

    public function getResultListForPage() {
        $params = $this->extractParams(true);
        $result = $this->queryAPI($params['sname'], $params['scountry'], $params['saddress'], $params['slist'], $params['smode'], (int)$params['sfuzziness'], (int)$params['spage']);

        if($result->results) {
            $listItems = $this->renderResultList($result->results);
            return json_encode([
                'success' => true,
                'resultList' => $listItems
            ]);
        } else {
            return json_encode([
                'success' => false
            ]);
        }
    }

    private function extractParams($post = false) {
        if($post) {
            $data = $_POST;
        } else {
            $data = $_GET;
        }

        $params['sname'] = sanitize_text_field($data['sname']);

        // default values for our search params
        $paramDefaults = [
            'scountry' => [],
            'saddress' => '',
            'slist' => [],
            'smode' => 'strict',
            'spage' => 1
        ];

        foreach($paramDefaults as $param => $value) {
            // check if value was passed, else set to default
            if(isset($data[$param]) && !empty($data[$param])) {
                if(!is_array($data[$param])){
                    $params[$param] = sanitize_text_field($data[$param]);
                }else{
                    $params[$param] = array_map( 'sanitize_text_field', $data[$param] );
                }
            } else {
                if(!is_array($value)){
                    $params[$param] = sanitize_text_field($value);
                }else{
                    $params[$param] = array_map( 'sanitize_text_field', $value );
                }
            }
        }

        return $params;
    }

    private function renderResultList($results) {
        ob_start();
        foreach($results as $item) {
        ?>
            <li>
                <div>
                    <?=$item->name?>
                </div>
                <div>
                    <?php if(property_exists($item,'type')){ ?>
                        <div class="inline">
                            <span>Type:</span> <?=$item->type ? $item->type : 'None'?>
                        </div>
                    <?php } ?>

                    <?php if(property_exists($item,'entity_number')){ ?>
                    <div class="inline">
                        <span>Entity Number:</span> <?=$item->entity_number ? $item->entity_number : 'None'?>
                    </div>
                    <?php } ?>

                    <?php if(property_exists($item,'source')){ ?>
                    <div class="inline">
                        <span>Source:</span> <?=preg_replace('/^.*\((.*?)\).*$/', '$1', $item->source)?>
                    </div>
                    <?php } ?>


                </div>
                <?php if(property_exists($item,'programs') && $item->programs): ?>
                    <div>
                        <span>Programs:</span> <?=count($item->programs) ? implode(', ', $item->programs) : 'None' ?>
                    </div>
                <?php endif; ?>
          
                <?php if(property_exists($item,'addresses') && count($item->addresses)): ?>
                    <div>
                        <span>Addresses:</span>
                        <ul>
                            <?php foreach($item->addresses as $address): ?>
                                <?php
                                /*$address_array = [];
                                foreach(['address', 'city', 'state', 'postal_code', 'country'] as $key) {
                                    if(!is_null($address->$key)) {
                                        $address_array[] = $key == 'country' ? self::COUNTRY_LIST[$address->$key] : $address->$key;
                                    }
                                }*/
                                ?>
                                <li>
                                    <?=$address?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <div class="sanctions-search-results-additional">
                    <?php
                    $simpleAdditionalFields = [
                        'title' => 'Title',
                        'remarks' => 'Remarks',
                        'citizenships' => 'Citizenships',
                        'nationalities' => 'Nationalities',
                        'federal_register_notice' => 'Federal Register Notice',
                        'start_date' => 'Start Date',
                        'end_date' => 'End Date',
                        'standard_order' => 'Standard Order',
                        'license_requirement' => 'License Requirement',
                        'license_policy' => 'License Policy',
                        'regime' => 'Regime',
                        'passport_details' => 'Passport Details'
                    ];

                    $simpleListFields = [
                        'alt_names' => 'Aliases',
                        'dates_of_birth' => 'Dates of birth',
                        'places_of_birth' => 'Places of birth',
                        'positions' => 'Positions',
                        'ni_numbers' => 'NI Numbers'
                    ]
                    ?>

                    <?php foreach($simpleAdditionalFields as $key => $name): ?>
                        <?php if(property_exists($item,'key') && $item->$key): ?>
                            <div>
                                <span><?=$name?>:</span> <?=is_array($item->$key) ? implode(', ', $item->$key) : $item->$key?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php foreach($simpleListFields as $key => $name): ?>
                        <?php if(property_exists($item,$key) && $item->$key): ?>
                            <div>
                                <span><?=$name?>:</span>
                                <ul>
                                    <?php foreach($item->$key as $value): ?>
                                        <li><?=$value?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </li>
        <?php
        }

        $retvalue = ob_get_clean();
        return $retvalue;
    }

    /**
     * Function to fetch results from API. All values are hard coded for now, because it will be switched soon.
     *
     * @param $search
     * @param array $country
     * @param string $address
     * @param array $lists
     * @param string $searchMode
     * @param int $page
     * @param string $method
     * @return array|mixed|object result of the api-call
     */
	 
    private function queryAPI($search, $country = [], $address = "", $lists = [], $searchMode = 'strict', $fuzziness = 85, $page = 1, $method='search') {
        // API Params

        $sanctions_apikey = get_option('sanctions-apikey');
        $api_key = $sanctions_apikey;
        $api_url = 'https://api.sanctions.io/' . $method . '/?';
        $userIP = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
        $params = [
            'api_key' => $api_key,
            'name' => $search,
            'address' => $address,
            'fuzzy_name' => $searchMode == 'fuzzy' ? 'true' : 'false',
            'fuzziness' => $fuzziness,
            'page' => $page,
            'x-ip' => $userIP
        ];
        if(is_array($country)){
            $params['countries'] = implode(',', $country);
        }
        if(is_array($lists)){
            $params['sources'] = implode(',', $lists);
        }
        

        $paramString = http_build_query($params);

        $result = wp_remote_request($api_url.$paramString,'GET');
        $result = wp_remote_retrieve_body($result);
        return json_decode($result);
    }
    private function queryAPIData($method) {
        return $this->queryAPI("", [], "", [], "", 85, 1, $method);
    }
}

function sanctions_search($attrs = [], $content = null, $tag = '') {
    $search = new SanctionsSearch();

    $attrs = shortcode_atts(['action' => '#'], $attrs, $tag);
    $actionTarget = $attrs['action'];

    return $search->searchPage($actionTarget);
}

function sanctions_search_results() {
    $search = new SanctionsSearch();
    return $search->renderResults();
}

function sanctions_include_style() {
    // Own CSS-Modifications
    wp_register_style('sanctions-style', plugins_url('css/style.css', __FILE__));
    wp_enqueue_style('sanctions-style');

    // Adding Select2 for nicer <select>-Layouts
    wp_register_style('select2', plugins_url('css/select2.min.css', __FILE__));
    wp_enqueue_style('select2');

    //wp_register_script('select2', plugins_url('js/select2.min.js', __FILE__));
    wp_register_script('select2', plugins_url('js/select2.min.js', __FILE__),array("jquery"));
    wp_enqueue_script('select2');

    // TODO: jQuery Fallback (most Themes include it anyway)
}

function sanctions_init_shortcode() {
    // Search Input Shortcode
    add_shortcode('sanctions-search', 'sanctions_search');

    // Search Result Shortcode
    add_shortcode('sanctions-search-results', 'sanctions_search_results');
}

function sanctions_ajax_load_page() {
    $search = new SanctionsSearch();
    echo $search->getResultListForPage();
    wp_die();
}

function sanctions_load_admin_style() {
	//Load Admin side CSS
	wp_enqueue_style( 'sanctions-admin-css', plugins_url('css/admin-style.css', __FILE__) );
}
	  	  
add_action('init', 'sanctions_init_shortcode');
add_action('wp_enqueue_scripts', 'sanctions_include_style');
add_action('wp_ajax_nopriv_sanctions_load_page', 'sanctions_ajax_load_page');
add_action('wp_ajax_sanctions_load_page', 'sanctions_ajax_load_page');
add_action( 'admin_enqueue_scripts', 'sanctions_load_admin_style' );

function sanctions_redirect_append_params($redirect) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $params = $_SESSION['sanctions_search_params'];

    if(!is_null($params)) {
        $redirect = add_query_arg($params, $redirect);
        $_SESSION['sanctions_search_params'] = null;
    }

    return $redirect;

}

function sanctions_set_redirect_params($message) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $data = $_GET;
    
    $params = [];

    // default values for our search params
    $paramDefaults = [
        'sname' => '',
        'scountry' => [],
        'saddress' => '',
        'slist' => [],
        'smode' => 'strict',
        'spage' => 1
    ];

    foreach($paramDefaults as $param => $value) {
        // check if value was passed, else set to default
        if(isset($data[$param]) && !empty($data[$param])) {
            if(!is_array($data[$param])){
                $params[$param] = sanitize_text_field($data[$param]);
            }else{
                $params[$param] = array_map( 'sanitize_text_field', $data[$param] );
            }
        }
    }

    $_SESSION['sanctions_search_params'] = $params;
    return $message;
}


add_filter('admin_init', 'sanctions_init_session');
function sanctions_init_session() {
    if(isset($_GET['page']) && $_GET['page'] === 'sanctions-apikey'){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
}




add_filter('woocommerce_login_redirect', 'sanctions_redirect_append_params', 50, 1);
add_filter('wc_memberships_product_viewing_restricted_message', 'sanctions_set_redirect_params', 10, 1);
add_filter('wc_memberships_product_purchasing_restricted_message', 'sanctions_set_redirect_params', 10, 1);
add_filter('wc_memberships_content_restricted_message', 'sanctions_set_redirect_params', 10, 1);
add_filter('wc_memberships_product_taxonomy_viewing_restricted_message', 'sanctions_set_redirect_params', 10, 1);