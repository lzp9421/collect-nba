<?php

namespace Multiple\Frontend\Controllers;

use Phalcon\Mvc\Controller;
use PHPHtmlParser\Dom;
use Multiple\Frontend\Models\NbaInjuries;
use Multiple\Frontend\Models\NbaPlayer;

class IndexController extends Controller
{
    public function indexAction()
    {
        $html = $this->getHtml('http://www.espn.com/nba/injuries');
        array_walk($this->parseHtml($html), function ($data) {
            $this->saveInjuries($data);
        });
        return json_encode(['status' => 'success'], JSON_UNESCAPED_UNICODE);
    }

    /**
     * 保存一条信息
     * @param $data
     */
    protected function saveInjuries($data)
    {
        $player_code = strtolower(str_replace(' ', '_', trim($data[1])));

        $injuries = NbaInjuries::findFirst([
            "conditions" => "playerCode = ?1",
            "bind"       => [
                1 => $player_code,
            ]
        ]);

        empty($injuries) && $injuries = new NbaInjuries;

        $player = NbaPlayer::findFirst([
            "conditions" => "playerCode = ?1",
            "bind"       => [
                1 => strtolower(str_replace(' ', '_', trim($data[1]))),
            ]
        ]);

        //
        $injuries->displayNameEn = $data['1'];
        $injuries->status = $data['2'];
        $injuries->date = $data['3'];
        $injuries->comment = $data['4'];
        $fields = ['playerId', 'playerCode', 'teamId', 'teamCode', 'teamName'];
        foreach ($fields as $field) {
            $injuries->$field = $player->$field;
        }
        $injuries->save();
    }

    /**
     * 下载url
     * @param string $url
     * @return string
     */
    protected function getHtml($url)
    {
        return <<< 'DOC'

<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head><script src="http://cdn.espn.com/sports/optimizely.js"></script><meta charset="iso-8859-1">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<link rel="icon" sizes="any" mask href="http://a.espncdn.com/favicon.ico">
<meta name="theme-color" content="#CC0000">
<script type="text/javascript">
    if(true && navigator && navigator.userAgent.toLowerCase().indexOf("teamstream") >= 0) {
        window.location = 'http://m.espn.com/mobilecache/general/apps/sc';
    }
</script><title>NBA Basketball Injuries - National Basketball Association Injuries - ESPN</title>
<meta name="google-site-verification" content="xuj1ODRluWa0frM-BjIr_aSHoUC7HB5C1MgmYAM_GkA" />
<meta name="msvalidate.01" content="B1FEB7C682C46C8FCDA3130F3D18AC28" />
<meta name="googlebot" content="noodp" />
<meta name="robots" content="index, follow" />
<meta name="description" content="Up to date and in-depth coverage of NBA injuries, sorted by team, status, date, and league." />
<meta name="keywords" content="NBA injuries, pro basketball injuries, injured NBA players, NBA player injury status" />
<meta property="fb:app_id" content="116656161708917">
<meta property="og:site_name" content="ESPN.com">
<!--
<PageMap>
	<DataObject type="document">
		<Attribute name="title">NBA Basketball Injuries - National Basketball Association Injuries</Attribute>
	</DataObject>
</PageMap>
--><link rel="canonical" href="http://www.espn.com/nba/injuries" />

		<script>var _sf_startpt=(new Date()).getTime();</script>
<link rel="search" type="application/opensearchdescription+xml" href="http://a.espncdn.com/search/opensearch.xml" title="ESPN Search" />
<link rel="stylesheet" href="http://a.espncdn.com/combiner/c/201501211964?css=global_reset.r1.css,base.r236.css,modules.r465.css,global_header.r44.css,header_topbar.r1.css,modules/global_nav.r54.css,modules/insider_enhanced.r1.css,modules/mem/mem.r12.1.css,modules/mem/mem_espn360.r8.4.css,modules/mem/skirmish.r8.css,modules/twin.r1.css,modules/facebook/button.r2.css,universal_overlay/universal_overlay.css,universal_overlay/media_overlay.css,universal_overlay/video_overlay.css,universal_overlay/photo_overlay.css,universal_overlay/dyk_overlay.css,fonts/bentonsans.css,fonts/bentonsansmedium.css" type="text/css" media="screen" charset="utf-8" />
<link rel="stylesheet" href="http://a.espncdn.com/combiner/c/201508060924?css=sprites/teamlogos.r19.css" type="text/css" media="screen" charset="utf-8" />
<link rel="stylesheet" href="http://a.espncdn.com/combiner/c/20120509325?css=modules/master_tables_09.r3.css" type="text/css" media="screen" charset="utf-8" />
<link rel="stylesheet" href="http://a.espncdn.com/prod/styles/transitionalHeader/overrides/transitional-base.css" type="text/css" media="screen" charset="utf-8" />
<link rel="stylesheet" href="http://a.espncdn.com/prod/styles/video/2.10.1/espn-web-player-bundle.css" />
<script src="http://widgets.outbrain.com/outbrain.js"></script>
<script type="text/javascript">
var _vrq = _vrq || [];
 _vrq.push(['id', 489]);
 _vrq.push(['automate', false]);
 _vrq.push(['track', function(){}]);
 (function(d, a){var s = d.createElement(a),x = d.getElementsByTagName(a)[0];
s.async = true;
s.src = 'http://a.visualrevenue.com/vrs.js';
 x.parentNode.insertBefore(s, x);})(document, 'script'); </script>
<script type="text/javascript" src="http://a.espncdn.com/legacy/desktop/1.0.34/js/espn-critical.js"></script>
<script>espn.core.navVersion = 'a';</script>
<script>var ad_site="espn.us.com.nba",ad_zone="nbainjuries",ad_kvps="pgtyp=nbainjuries;sp=nba;nav=true;",ad_swid="",ad_counter=1,ad_ord=Math.floor(9999999999*Math.random()),ad_seg="",ad_mnr=-1<document.cookie.indexOf("grif=1")?"t":"f",ad_ref="other",ad_sp="nba",ad_pgtyp="nbainjuries",ad_pgn="",ref=document.referrer,refsMap={"facebook.com":"facebook","twitter.com|t.co":"twitter","plus.url.google.com|plus.google.com":"googleplus","google.com":"google","bing.com":"bing","yahoo.com":"yahoo","espn.com":"espn"},refKey;for(refKey in refsMap)ref.match(refKey)&&(ad_ref=refsMap[refKey]);
ad_kvps=ad_kvps+"ref="+ad_ref+";mnr="+ad_mnr+";";ad_swid=jQuery.cookie("SWID");"function"===typeof espn.core.ad_segments&&(ad_seg=espn.core.ad_segments(),null!=ad_seg&&""!=ad_seg&&(ad_kvps+=ad_seg));var ad_u="";null!=ad_swid&&""!=ad_swid&&(ad_u="swid="+ad_swid+";");</script>
<link rel="stylesheet" href="http://a.espncdn.com/legacy/transitionalHeader/1.0.38/css/transitional-header.css" type="text/css" media="screen" charset="utf-8" />
</head>
<body class="nba nba-secondary-bg {sportId:46}"  data-sport="nba" data-pagetype="nbainjuries">
<!--[if IE 5]><div class="ie5"><![endif]-->
<!--[if IE 6]><div class="ie ie6"><![endif]-->
<!--[if IE 7]><div class="ie ie7"><![endif]-->
<!--[if IE 8]><div class="ie ie8"><![endif]-->
<!--[if IE 9]><div class="ie9"><![endif]-->
<!-- begin bg-elements -->
<div class="bg-elements">

<!-- gpt ad type: background_skin -->
<div class="ad-slot ad-slot-background_skin" data-slot-type="background_skin" data-slot-kvps="pos=background_skin" data-exclude-bp="s,m" data-collapse-before-load="true"></div><!-- begin subheader -->
<div id="subheader">
<!-- begin content -->
<div id="content-wrapper">
<div id="ad-top" class="container">
<!-- begin Banner ad -->
<div class="span-6 ad banner top"><div class="ad-center"><div class="ad-wrapper">
<!-- gpt ad type: banner -->
<div class="ad-slot ad-slot-banner" data-slot-type="banner" data-slot-kvps="pos=banner"></div></div></div></div>
<!-- end Banner ad -->
</div>
<div id="content" class="container">
<div id="fb-root"></div><!--[if lte IE 7]><link rel="stylesheet" charset="utf-8" media="screen" href="http://a.espncdn.com/combiner/c?css=modules/browser-alert.r7.css" type="text/css" /><div class="span-6" id="browser-alert-wrapper" style="display:none;"><div class="mod-container mod-no-header-footer browser-alert"><div class="mod-content"><div class="gradient-container"><h1><span>Your Web Browser</span> is no longer supported</h1><p>To experience everything that ESPN.com has to offer, we recommend that you upgrade to a newer version of your web browser. Click the upgrade button to the right or <a href="http://www.espn.com/espn/news/story/_/id/5630562">learn more</a>.</p><ul><li><a class="btn-upgrade" href="http://www.microsoft.com/windows/internet-explorer/default.aspx">Upgrade</a></li><li><a class="btn-faq" href="http://www.espn.com/espn/news/story/_/id/5630562">FAQs</a></li></ul></div></div></div></div><script>(function(b){setTimeout(function(){var a=b("#browser-alert-wrapper");if(espn.cookie.get("browseralert")!="true"){b('<a class="btn-close" href="#">Close</a>').bind("click",function(){a.hide();espn.cookie.set("browseralert","true",30);return false}).appendTo(a.find(".gradient-container"));a.show();espn.cookie.set("browseralert","true")}},100)})(jQuery);</script><![endif]-->

	<style type="text/css">
		.playerrow td img{ margin: 3px 10px 0; }
		.playerrow td{ vertical-align: top; }
		.playerrow td .floatleft, .playerrow td .floatright{ margin: 5px 0 10px; }
	</style>

	<div class="span-6">
		<div class="mod-container mod-no-header-footer mod-page-header">
			<div class="mod-content">
				<h1 class="h2">
					NBA Injuries
				</h1>
				<div class="floatleft">
					Conference:
					NBA | <a href="//www.espn.com/nba/injuries/_/conference/eastern">Eastern</a> | <a href="//www.espn.com/nba/injuries/_/conference/western">Western</a>
					<br/>
					Team:
					<form class="js-goto" style="display: inline;">
						<select class="tablesm">
							<option value="//www.espn.com/nba/injuries">All Teams</option>
						<option value="//www.espn.com/nba/injuries/_/team/atl">Atlanta</option>
<option value="//www.espn.com/nba/injuries/_/team/bos">Boston</option>
<option value="//www.espn.com/nba/injuries/_/team/bkn">Brooklyn</option>
<option value="//www.espn.com/nba/injuries/_/team/cha">Charlotte</option>
<option value="//www.espn.com/nba/injuries/_/team/chi">Chicago</option>
<option value="//www.espn.com/nba/injuries/_/team/cle">Cleveland</option>
<option value="//www.espn.com/nba/injuries/_/team/dal">Dallas</option>
<option value="//www.espn.com/nba/injuries/_/team/den">Denver</option>
<option value="//www.espn.com/nba/injuries/_/team/det">Detroit</option>
<option value="//www.espn.com/nba/injuries/_/team/gsw">Golden State</option>
<option value="//www.espn.com/nba/injuries/_/team/hou">Houston</option>
<option value="//www.espn.com/nba/injuries/_/team/ind">Indiana</option>
<option value="//www.espn.com/nba/injuries/_/team/lac">LA Clippers</option>
<option value="//www.espn.com/nba/injuries/_/team/lal">LA Lakers</option>
<option value="//www.espn.com/nba/injuries/_/team/mem">Memphis</option>
<option value="//www.espn.com/nba/injuries/_/team/mia">Miami</option>
<option value="//www.espn.com/nba/injuries/_/team/mil">Milwaukee</option>
<option value="//www.espn.com/nba/injuries/_/team/min">Minnesota</option>
<option value="//www.espn.com/nba/injuries/_/team/nor">New Orleans</option>
<option value="//www.espn.com/nba/injuries/_/team/nyk">New York</option>
<option value="//www.espn.com/nba/injuries/_/team/okc">Oklahoma City</option>
<option value="//www.espn.com/nba/injuries/_/team/orl">Orlando</option>
<option value="//www.espn.com/nba/injuries/_/team/phi">Philadelphia</option>
<option value="//www.espn.com/nba/injuries/_/team/pho">Phoenix</option>
<option value="//www.espn.com/nba/injuries/_/team/por">Portland</option>
<option value="//www.espn.com/nba/injuries/_/team/sac">Sacramento</option>
<option value="//www.espn.com/nba/injuries/_/team/sas">San Antonio</option>
<option value="//www.espn.com/nba/injuries/_/team/tor">Toronto</option>
<option value="//www.espn.com/nba/injuries/_/team/uth">Utah</option>
<option value="//www.espn.com/nba/injuries/_/team/was">Washington</option>

						</select>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="span-6">
		<div class="span-4" id="my-players-table">
			<div class="mod-container mod-table mod-no-header">
				<div class="mod-content">
					<table cellpadding="3" cellspacing="1" class="tablehead">
						<tr class="stathead"><td colspan="3">Atlanta Hawks</td></tr><tr class="colhead"><td>NAME</td><td>STATUS</td><td>DATE</td></tr><tr class="oddrow player-46-3015"><td><a href="http://www.espn.com/nba/player/_/id/3015/paul-millsap">Paul Millsap</a></td><td>Out</td><td>Mar 27</td></tr><tr class="oddrow player-46-3015"><td colspan="3"><em>Comment: </em>Millsap is dealing with left knee synovitis and will miss at least the next three games, Chris Vivlamore of the Atlanta Journal-Constitution reports.</td></tr>
<tr class="evenrow player-46-3028"><td><a href="http://www.espn.com/nba/player/_/id/3028/thabo-sefolosha">Thabo Sefolosha</a></td><td>Day-To-Day</td><td>Mar 27</td></tr><tr class="evenrow player-46-3028"><td colspan="3"><em>Comment: </em>Sefolosha (groin) has been ruled out of Tuesday's contest against the Suns, Chris Vivlamore of The Atlanta Journal-Constitution reports.</td></tr>
<tr class="oddrow player-46-3015"><td><a href="http://www.espn.com/nba/player/_/id/3015/paul-millsap">Paul Millsap</a></td><td>Out</td><td>Mar 27</td></tr><tr class="oddrow player-46-3015"><td colspan="3"><em>Comment: </em>Millsap (knee) has been ruled out of Tuesday's game against the Suns, Chris Vivlamore of the Atlanta Journal-Constitution reports.</td></tr>
<tr class="evenrow player-46-6637"><td><a href="http://www.espn.com/nba/player/_/id/6637/kent-bazemore">Kent Bazemore</a></td><td>Out</td><td>Mar 27</td></tr><tr class="evenrow player-46-6637"><td colspan="3"><em>Comment: </em>Bazemore (knee) could return for Wednesday's game against the 76ers, Chris Vivlamore of the Atlanta Journal-Constitution reports.</td></tr>
<tr class="oddrow player-46-3028"><td><a href="http://www.espn.com/nba/player/_/id/3028/thabo-sefolosha">Thabo Sefolosha</a></td><td>Day-To-Day</td><td>Mar 26</td></tr><tr class="oddrow player-46-3028"><td colspan="3"><em>Comment: </em>day-to-day</td></tr>
<tr class="evenrow player-46-3028"><td><a href="http://www.espn.com/nba/player/_/id/3028/thabo-sefolosha">Thabo Sefolosha</a></td><td>Day-To-Day</td><td>Mar 26</td></tr><tr class="evenrow player-46-3028"><td colspan="3"><em>Comment: </em>Sefolosha is dealing with a strained right groin and has been ruled out for Sunday's game against the Nets, Chris Vivlamore of the Atlanta Journal-Constitution reports.</td></tr>
<tr class="oddrow player-46-3015"><td><a href="http://www.espn.com/nba/player/_/id/3015/paul-millsap">Paul Millsap</a></td><td>Out</td><td>Mar 26</td></tr><tr class="oddrow player-46-3015"><td colspan="3"><em>Comment: </em>Millsap, who's already been ruled out for Sunday's game against the Nets, recently had an MRI on his knee come back clean, Chris Vivlamore of the Atlanta Journal-Constitution reports.</td></tr>
<tr class="evenrow player-46-3015"><td><a href="http://www.espn.com/nba/player/_/id/3015/paul-millsap">Paul Millsap</a></td><td>Out</td><td>Mar 25</td></tr><tr class="evenrow player-46-3015"><td colspan="3"><em>Comment: </em>Millsap (knee) has been ruled out for Sunday's game against the Nets, Chris Vivlamore of the Atlanta Journal-Constitution reports.</td></tr>
<tr class="oddrow player-46-6637"><td><a href="http://www.espn.com/nba/player/_/id/6637/kent-bazemore">Kent Bazemore</a></td><td>Out</td><td>Mar 24</td></tr><tr class="oddrow player-46-6637"><td colspan="3"><em>Comment: </em>Bazemore (knee) has been ruled out for games Friday against the Bucks and Sunday against the Nets, Chris Vivlamore of the Atlanta Journal-Constitution reports.</td></tr>
<tr class="evenrow player-46-3015"><td><a href="http://www.espn.com/nba/player/_/id/3015/paul-millsap">Paul Millsap</a></td><td>Out</td><td>Mar 24</td></tr><tr class="evenrow player-46-3015"><td colspan="3"><em>Comment: </em>Coach Mike Budenholzer acknowledged that it hasn't been determined if Millsap (knee), who has been ruled out for Friday's game against the Bucks, will be ready to play Sunday against the Nets, Chris Vivlamore of the Atlanta Journal-Constitution reports.</td></tr>
<tr class="stathead"><td colspan="3">Boston Celtics</td></tr><tr class="colhead"><td>NAME</td><td>STATUS</td><td>DATE</td></tr><tr class="oddrow player-46-4240"><td><a href="http://www.espn.com/nba/player/_/id/4240/avery-bradley">Avery Bradley</a></td><td>Day-To-Day</td><td>Mar 26</td></tr><tr class="oddrow player-46-4240"><td colspan="3"><em>Comment: </em>Bradley (illness) is listed as questionable for Sunday's game against the Heat.</td></tr>
<tr class="evenrow player-46-4240"><td><a href="http://www.espn.com/nba/player/_/id/4240/avery-bradley">Avery Bradley</a></td><td>Day-To-Day</td><td>Mar 24</td></tr><tr class="evenrow player-46-4240"><td colspan="3"><em>Comment: </em>Bradley has been ruled out of Friday's game against the Suns due to illness, Celtics commentator Sean Grande reports.</td></tr>
<tr class="stathead"><td colspan="3">Brooklyn Nets</td></tr><tr class="colhead"><td>NAME</td><td>STATUS</td><td>DATE</td></tr><tr class="oddrow player-46-2528794"><td><a href="http://www.espn.com/nba/player/_/id/2528794/joe-harris">Joe Harris</a></td><td>Out</td><td>Mar 27</td></tr><tr class="oddrow player-46-2528794"><td colspan="3"><em>Comment: </em>Harris (shoulder) will remain out Tuesday against the 76ers, Brian Lewis of the New York Post reports.</td></tr>
<tr class="evenrow player-46-2488689"><td><a href="http://www.espn.com/nba/player/_/id/2488689/sean-kilpatrick">Sean Kilpatrick</a></td><td>Out</td><td>Mar 27</td></tr><tr class="evenrow player-46-2488689"><td colspan="3"><em>Comment: </em>Kilpatrick (hamstring) is listed as probable for Tuesday's game against the 76ers, Brian Lewis of the New York Post reports.</td></tr>
<tr class="oddrow player-46-3064291"><td><a href="http://www.espn.com/nba/player/_/id/3064291/rondae-hollis-jefferson">Rondae Hollis-Jefferson</a></td><td>Day-To-Day</td><td>Mar 26</td></tr><tr class="oddrow player-46-3064291"><td colspan="3"><em>Comment: </em>Hollis-Jefferson is dealing with an illness and is a game-time decision for Sunday's matchup with the Hawks, KL Chouinard of Hawks.com reports.</td></tr>
<tr class="evenrow player-46-2528794"><td><a href="http://www.espn.com/nba/player/_/id/2528794/joe-harris">Joe Harris</a></td><td>Out</td><td>Mar 25</td></tr><tr class="evenrow player-46-2528794"><td colspan="3"><em>Comment: </em>Harris (shoulder) has been ruled out of Sunday's game against Atlanta, Brian Lewis of the New York Post reports.</td></tr>
<tr class="oddrow player-46-2488689"><td><a href="http://www.espn.com/nba/player/_/id/2488689/sean-kilpatrick">Sean Kilpatrick</a></td><td>Out</td><td>Mar 25</td></tr><tr class="oddrow player-46-2488689"><td colspan="3"><em>Comment: </em>Kilpatrick (hamstring) will not play Sunday against Atlanta, Brian Lewis of the New York Post reports.</td></tr>
<tr class="evenrow player-46-2488689"><td><a href="http://www.espn.com/nba/player/_/id/2488689/sean-kilpatrick">Sean Kilpatrick</a></td><td>Out</td><td>Mar 24</td></tr><tr class="evenrow player-46-2488689"><td colspan="3"><em>Comment: </em>Kilpatrick (hamstring) won't be available Friday against the Wizards.</td></tr>
<tr class="oddrow player-46-2528794"><td><a href="http://www.espn.com/nba/player/_/id/2528794/joe-harris">Joe Harris</a></td><td>Out</td><td>Mar 24</td></tr><tr class="oddrow player-46-2528794"><td colspan="3"><em>Comment: </em>Harris (shoulder) has been ruled out for Friday's game against the Wizards.</td></tr>
<tr class="stathead"><td colspan="3">Charlotte Hornets</td></tr><tr class="colhead"><td>NAME</td><td>STATUS</td><td>DATE</td></tr><tr class="oddrow player-46-6616"><td><a href="http://www.espn.com/nba/player/_/id/6616/miles-plumlee">Miles Plumlee</a></td><td>Out</td><td>Mar 26</td></tr><tr class="oddrow player-46-6616"><td colspan="3"><em>Comment: </em>Plumlee (calf) is no longer listed on the Hornets' injury report, Stephanie Ready of FOX Sports Southeast reports.</td></tr>
<tr class="evenrow player-46-3231"><td><a href="http://www.espn.com/nba/player/_/id/3231/ramon-sessions">Ramon Sessions</a></td><td>Out</td><td>Mar 26</td></tr><tr class="evenrow player-46-3231"><td colspan="3"><em>Comment: </em>Sessions (knee) has been ruled out for Sunday's game against the Suns, Stephanie Ready of FOX Sports Southeast reports.</td></tr>
<tr class="stathead"><td colspan="3">Chicago Bulls</td></tr><tr class="colhead"><td>NAME</td><td>STATUS</td><td>DATE</td></tr><tr class="oddrow player-46-3113587"><td><a href="http://www.espn.com/nba/player/_/id/3113587/cristiano-felicio">Cristiano Felicio</a></td><td>Out</td><td>Mar 26</td></tr><tr class="oddrow player-46-3113587"><td colspan="3"><em>Comment: </em>out</td></tr>
<tr class="evenrow player-46-3113587"><td><a href="http://www.espn.com/nba/player/_/id/3113587/cristiano-felicio">Cristiano Felicio</a></td><td>Day-To-Day</td><td>Mar 25</td></tr><tr class="evenrow player-46-3113587"><td colspan="3"><em>Comment: </em>Felicio (back) will not play Sunday against Milwaukee, KC Johnson of the Chicago Tribune reports.</td></tr>
<tr class="oddrow player-46-3113587"><td><a href="http://www.espn.com/nba/player/_/id/3113587/cristiano-felicio">Cristiano Felicio</a></td><td>Day-To-Day</td><td>Mar 24</td></tr><tr class="oddrow player-46-3113587"><td colspan="3"><em>Comment: </em>Felicio (back) has been ruled out for Friday's game against the 76ers, K.C. Johnson of the Chicago Tribune reports.</td></tr>
<tr class="stathead"><td colspan="3">Cleveland Cavaliers</td></tr><tr class="colhead"><td>NAME</td><td>STATUS</td><td>DATE</td></tr><tr class="oddrow player-46-2011"><td><a href="http://www.espn.com/nba/player/_/id/2011/kyle-korver">Kyle Korver</a></td><td>Day-To-Day</td><td>Mar 27</td></tr><tr class="oddrow player-46-2011"><td colspan="3"><em>Comment: </em>Korver (foot) will miss at least the next three games, Joe Vardon of Cleveland.com reports.</td></tr>
<tr class="evenrow player-46-6468"><td><a href="http://www.espn.com/nba/player/_/id/6468/iman-shumpert">Iman Shumpert</a></td><td>Day-To-Day</td><td>Mar 27</td></tr><tr class="evenrow player-46-6468"><td colspan="3"><em>Comment: </em>Shumpert (knee) has been ruled out for Monday's game against the Spurs, Joe Vardon of Cleveland.com reports.</td></tr>
<tr class="oddrow player-46-6468"><td><a href="http://www.espn.com/nba/player/_/id/6468/iman-shumpert">Iman Shumpert</a></td><td>Day-To-Day</td><td>Mar 27</td></tr><tr class="oddrow player-46-6468"><td colspan="3"><em>Comment: </em>Shumpert (knee) is questionable to participate in Monday's tilt against the Spurs, Maria Ridenour of the Akron Beacon Journal reports.</td></tr>
<tr class="evenrow player-46-2011"><td><a href="http://www.espn.com/nba/player/_/id/2011/kyle-korver">Kyle Korver</a></td><td>Day-To-Day</td><td>Mar 27</td></tr><tr class="evenrow player-46-2011"><td colspan="3"><em>Comment: </em>Korver will not play in Monday's game against the Spurs due to left soreness again, Maria Ridenour of the Akron Beacon Journal reports.</td></tr>
<tr class="oddrow player-46-6468"><td><a href="http://www.espn.com/nba/player/_/id/6468/iman-shumpert">Iman Shumpert</a></td><td>Day-To-Day</td><td>Mar 25</td></tr><tr class="oddrow player-46-6468"><td colspan="3"><em>Comment: </em>Shumpert will not play in Saturday's tilt against the Wizards due to right knee soreness, Jason Lloyd of The Athletic reports.</td></tr>
<tr class="evenrow player-46-1966"><td><a href="http://www.espn.com/nba/player/_/id/1966/lebron-james">LeBron James</a></td><td>Day-To-Day</td><td>Mar 25</td></tr><tr class="evenrow player-46-1966"><td colspan="3"><em>Comment: </em>James suffered a scratched cornea in Friday's win over the Hornets, Cleveland.com reports.</td></tr>
<tr class="stathead"><td colspan="3">Dallas Mavericks</td></tr><tr class="colhead"><td>NAME</td><td>STATUS</td><td>DATE</td></tr><tr class="oddrow player-46-2326307"><td><a href="http://www.espn.com/nba/player/_/id/2326307/seth-curry">Seth Curry</a></td><td>Day-To-Day</td><td>Mar 27</td></tr><tr class="oddrow player-46-2326307"><td colspan="3"><em>Comment: </em>Curry (shoulder) will not play in Monday's game against the Thunder, Mavericks' television play-by-play announcer Mark Followill reports.</td></tr>
<tr class="stathead"><td colspan="3">Denver Nuggets</td></tr><tr class="colhead"><td>NAME</td><td>STATUS</td><td>DATE</td></tr><tr class="oddrow player-46-3428"><td><a href="http://www.espn.com/nba/player/_/id/3428/danilo-gallinari">Danilo Gallinari</a></td><td>Out</td><td>Mar 24</td></tr><tr class="oddrow player-46-3428"><td colspan="3"><em>Comment: </em>Gallinari scored 21 points (2-12 FG, 0-6 3Pt, 17-18 FT) to go along with 11 rebounds, two assists, two blocked shots and a steal across 35 minutes in Friday's 125-117 win over the Pacers.</td></tr>
<tr class="evenrow player-46-6433"><td><a href="http://www.espn.com/nba/player/_/id/6433/kenneth-faried">Kenneth Faried</a></td><td>Day-To-Day</td><td>Mar 24</td></tr><tr class="evenrow player-46-6433"><td colspan="3"><em>Comment: </em>day-to-day</td></tr>
<tr class="oddrow player-46-6433"><td><a href="http://www.espn.com/nba/player/_/id/6433/kenneth-faried">Kenneth Faried</a></td><td>Day-To-Day</td><td>Mar 24</td></tr><tr class="oddrow player-46-6433"><td colspan="3"><em>Comment: </em>Faried is dealing with a left shoulder contusion and is listed as questionable for Friday's game against the Pacers, Chris Dempsey of Altitude Sports reports.</td></tr>
<tr class="evenrow player-46-3428"><td><a href="http://www.espn.com/nba/player/_/id/3428/danilo-gallinari">Danilo Gallinari</a></td><td>Out</td><td>Mar 24</td></tr><tr class="evenrow player-46-3428"><td colspan="3"><em>Comment: </em>Gallinari (knee) will play Friday against the Pacers, Chris Dempsey of Altitude Sports reports.</td></tr>
<tr class="stathead"><td colspan="3">Detroit Pistons</td></tr><tr class="colhead"><td>NAME</td><td>STATUS</td><td>DATE</td></tr><tr class="oddrow player-46-6443"><td><a href="http://www.espn.com/nba/player/_/id/6443/reggie-jackson">Reggie Jackson</a></td><td>Day-To-Day</td><td>Mar 27</td></tr><tr class="oddrow player-46-6443"><td colspan="3"><em>Comment: </em>Jackson (knee) is not expected to play Tuesday against the Heat, Rod Beard of The Detroit News reports.</td></tr>
<tr class="evenrow player-46-6443"><td><a href="http://www.espn.com/nba/player/_/id/6443/reggie-jackson">Reggie Jackson</a></td><td>Day-To-Day</td><td>Mar 27</td></tr><tr class="evenrow player-46-6443"><td colspan="3"><em>Comment: </em>day-to-day</td></tr>
<tr class="oddrow player-46-6443"><td><a href="http://www.espn.com/nba/player/_/id/6443/reggie-jackson">Reggie Jackson</a></td><td>Day-To-Day</td><td>Mar 27</td></tr><tr class="oddrow player-46-6443"><td colspan="3"><em>Comment: </em>Jackson will sit out Monday's game against the Knicks for rest, Aaron McMann of MLive.com reports.</td></tr>
<tr class="evenrow player-46-2528779"><td><a href="http://www.espn.com/nba/player/_/id/2528779/reggie-bullock">Reggie Bullock</a></td><td>Day-To-Day</td><td>Mar 27</td></tr><tr class="evenrow player-46-2528779"><td colspan="3"><em>Comment: </em>Bullock suffered from a right foot sprain and has been ruled out for Monday's game against the Knicks, Aaron McMann of MLive.com reports.</td></tr>
<tr class="oddrow player-46-2566747"><td><a href="http://www.espn.com/nba/player/_/id/2566747/michael-gbinije">Michael Gbinije</a></td><td>Day-To-Day</td><td>Mar 24</td></tr><tr class="oddrow player-46-2566747"><td colspan="3"><em>Comment: </em>Gbinije (ankle) is listed in the game notes as doubtful for Friday's tilt with the Magic.</td></tr>
<tr class="stathead"><td colspan="3">Golden State Warriors</td></tr><tr class="colhead"><td>NAME</td><td>STATUS</td><td>DATE</td></tr><tr class="oddrow player-46-3202"><td><a href="http://www.espn.com/nba/player/_/id/3202/kevin-durant">Kevin Durant</a></td><td>Out</td><td>Mar 26</td></tr><tr class="oddrow player-46-3202"><td colspan="3"><em>Comment: </em>Durant (knee) is expected to be on a minutes restriction when he returns to game action, Connor Letourneau of the San Francisco Chronicle reports. "It's something we'll consult the training staff on," head coach Steve Kerr said after practice Saturday. "I imagine we'll ease him back by playing him shorter minutes to start, so he can build up his rhythm and his conditioning."</td></tr>
<tr class="evenrow player-46-3202"><td><a href="http://www.espn.com/nba/player/_/id/3202/kevin-durant">Kevin Durant</a></td><td>Out</td><td>Mar 24</td></tr><tr class="evenrow player-46-3202"><td colspan="3"><em>Comment: </em>Durant saw involvement in a number of on-court activities during the Warriors' morning shootaround Friday, Monte Poole of CSN Bay Area reports.</td></tr>
<tr class="stathead"><td colspan="3">Houston Rockets</td></tr><tr class="colhead"><td>NAME</td><td>STATUS</td><td>DATE</td></tr><tr class="oddrow player-46-3412"><td><a href="http://www.espn.com/nba/player/_/id/3412/ryan-anderson">Ryan Anderson</a></td><td>Out</td><td>Mar 26</td></tr><tr class="oddrow player-46-3412"><td colspan="3"><em>Comment: </em>out</td></tr>
<tr class="evenrow player-46-3412"><td><a href="http://www.espn.com/nba/player/_/id/3412/ryan-anderson">Ryan Anderson</a></td><td>Out</td><td>Mar 26</td></tr><tr class="evenrow player-46-3412"><td colspan="3"><em>Comment: </em>Anderson (ankle) has been ruled out for the next two weeks, Mark Berman of Fox 26 Houston reports.</td></tr>
<tr class="oddrow player-46-3412"><td><a href="http://www.espn.com/nba/player/_/id/3412/ryan-anderson">Ryan Anderson</a></td><td>Day-To-Day</td><td>Mar 26</td></tr><tr class="oddrow player-46-3412"><td colspan="3"><em>Comment: </em>Coach Mike D'Antoni said Anderson (ankle), who has been ruled out Sunday against the Thunder, will also "probably" sit out Tuesday against the Warriors, Jonathan Feigen of the Houston Chronicle reports.</td></tr>
<tr class="evenrow player-46-3412"><td><a href="http://www.espn.com/nba/player/_/id/3412/ryan-anderson">Ryan Anderson</a></td><td>Day-To-Day</td><td>Mar 25</td></tr><tr class="evenrow player-46-3412"><td colspan="3"><em>Comment: </em>Anderson (ankle) will not play Sunday against the Thunder, Calvin Watkins of ESPN reports.</td></tr>
<tr class="oddrow player-46-3412"><td><a href="http://www.espn.com/nba/player/_/id/3412/ryan-anderson">Ryan Anderson</a></td><td>Day-To-Day</td><td>Mar 24</td></tr><tr class="oddrow player-46-3412"><td colspan="3"><em>Comment: </em>Anderson was forced to leave Friday's matchup against the Pelicans early due to an injured ankle, JonathanFeigan of the Houston Chronicle reports.</td></tr>
<tr class="stathead"><td colspan="3">Indiana Pacers</td></tr><tr class="colhead"><td>NAME</td><td>STATUS</td><td>DATE</td></tr><tr class="oddrow player-46-3235"><td><a href="http://www.espn.com/nba/player/_/id/3235/rodney-stuckey">Rodney Stuckey</a></td><td>Day-To-Day</td><td>Mar 27</td></tr><tr class="oddrow player-46-3235"><td colspan="3"><em>Comment: </em>Stuckey (knee), who is schedule to undergo an MRI on Monday, is officially listed as questionable for Tuesday's game against the Timberwolves.</td></tr>
<tr class="evenrow player-46-3235"><td><a href="http://www.espn.com/nba/player/_/id/3235/rodney-stuckey">Rodney Stuckey</a></td><td>Day-To-Day</td><td>Mar 27</td></tr><tr class="evenrow player-46-3235"><td colspan="3"><em>Comment: </em>Stuckey (knee) is scheduled to undergo an MRI on Monday.</td></tr>
<tr class="oddrow player-46-2389"><td><a href="http://www.espn.com/nba/player/_/id/2389/al-jefferson">Al Jefferson</a></td><td>Out</td><td>Mar 27</td></tr><tr class="oddrow player-46-2389"><td colspan="3"><em>Comment: </em>Head coach Nate McMillan said Jefferson (ankle) is out for at least "a few days," Pacers radio announcer Pat Boylan reports.</td></tr>
<tr class="evenrow player-46-2389"><td><a href="http://www.espn.com/nba/player/_/id/2389/al-jefferson">Al Jefferson</a></td><td>Out</td><td>Mar 27</td></tr><tr class="evenrow player-46-2389"><td colspan="3"><em>Comment: </em>out</td></tr>
<tr class="oddrow player-46-2389"><td><a href="http://www.espn.com/nba/player/_/id/2389/al-jefferson">Al Jefferson</a></td><td>Day-To-Day</td><td>Mar 26</td></tr><tr class="oddrow player-46-2389"><td colspan="3"><em>Comment: </em>Jefferson (ankle) had his X-rays come back negative following Sunday's 107-94 victory over the 76ers, Nate Taylor of the Indianapolis Star reports.</td></tr>
<tr class="evenrow player-46-3235"><td><a href="http://www.espn.com/nba/player/_/id/3235/rodney-stuckey">Rodney Stuckey</a></td><td>Day-To-Day</td><td>Mar 26</td></tr><tr class="evenrow player-46-3235"><td colspan="3"><em>Comment: </em>day-to-day</td></tr>
<tr class="oddrow player-46-3235"><td><a href="http://www.espn.com/nba/player/_/id/3235/rodney-stuckey">Rodney Stuckey</a></td><td>Day-To-Day</td><td>Mar 26</td></tr><tr class="oddrow player-46-3235"><td colspan="3"><em>Comment: </em>Stuckey has a sore left knee and will not return to Sunday's game against the 76ers.</td></tr>
<tr class="evenrow player-46-2389"><td><a href="http://www.espn.com/nba/player/_/id/2389/al-jefferson">Al Jefferson</a></td><td>Day-To-Day</td><td>Mar 26</td></tr><tr class="evenrow player-46-2389"><td colspan="3"><em>Comment: </em>day-to-day</td></tr>
<tr class="oddrow player-46-2389"><td><a href="http://www.espn.com/nba/player/_/id/2389/al-jefferson">Al Jefferson</a></td><td>Day-To-Day</td><td>Mar 26</td></tr><tr class="oddrow player-46-2389"><td colspan="3"><em>Comment: </em>Jefferson will not return to Sunday's game against the 76ers after suffering a left ankle sprain, Nate Taylor of the Indianapolis Star reports.</td></tr>
<tr class="stathead"><td colspan="3">LA Clippers</td></tr><tr class="colhead"><td>NAME</td><td>STATUS</td><td>DATE</td></tr><tr class="oddrow player-46-3924880"><td><a href="http://www.espn.com/nba/player/_/id/3924880/diamond-stone">Diamond Stone</a></td><td>Out</td><td>Mar 26</td></tr><tr class="oddrow player-46-3924880"><td colspan="3"><em>Comment: </em>Stone (knee) has been ruled out for Sunday's game against the Kings.</td></tr>
<tr class="evenrow player-46-3024"><td><a href="http://www.espn.com/nba/player/_/id/3024/jj-redick">JJ Redick</a></td><td>Day-To-Day</td><td>Mar 26</td></tr><tr class="evenrow player-46-3024"><td colspan="3"><em>Comment: </em>day-to-day</td></tr>
<tr class="oddrow player-46-3024"><td><a href="http://www.espn.com/nba/player/_/id/3024/jj-redick">JJ Redick</a></td><td>Day-To-Day</td><td>Mar 26</td></tr><tr class="oddrow player-46-3024"><td colspan="3"><em>Comment: </em>Redick is dealing with a sprained right ankle and has been ruled out for Sunday's game against the Kings, Bill Oram of the Orange County Register reports.</td></tr>
<tr class="stathead"><td colspan="3">Los Angeles Lakers</td></tr><tr class="colhead"><td>NAME</td><td>STATUS</td><td>DATE</td></tr><tr class="oddrow player-46-3913176"><td><a href="http://www.espn.com/nba/player/_/id/3913176/brandon-ingram">Brandon Ingram</a></td><td>Day-To-Day</td><td>Mar 27</td></tr><tr class="oddrow player-46-3913176"><td colspan="3"><em>Comment: </em>Ingram (knee) was held out of Monday's practice and is listed as doubtful for Tuesday's game against the Wizards, Mike Bresnahan of Spectrum SportsNet reports.</td></tr>
<tr class="evenrow player-46-3913176"><td><a href="http://www.espn.com/nba/player/_/id/3913176/brandon-ingram">Brandon Ingram</a></td><td>Day-To-Day</td><td>Mar 26</td></tr><tr class="evenrow player-46-3913176"><td colspan="3"><em>Comment: </em>Ingram (knee) will not play in Sunday's game against the Trail Blazers, Mike Trudell of ESPN Los Angeles reports.</td></tr>
<tr class="oddrow player-46-3913176"><td><a href="http://www.espn.com/nba/player/_/id/3913176/brandon-ingram">Brandon Ingram</a></td><td>Day-To-Day</td><td>Mar 25</td></tr><tr class="oddrow player-46-3913176"><td colspan="3"><em>Comment: </em>Ingram (knee) is listed as questionable for Sunday's game against Portland, Mark Medina of the LA Daily News reports.</td></tr>
<tr class="evenrow player-46-3913176"><td><a href="http://www.espn.com/nba/player/_/id/3913176/brandon-ingram">Brandon Ingram</a></td><td>Day-To-Day</td><td>Mar 24</td></tr><tr class="evenrow player-46-3913176"><td colspan="3"><em>Comment: </em>Ingram will not return to Friday's matchup versus the Timberwolves due to tendinitis in his knee, Mike Trudell of Lakers.com reports.</td></tr>
<tr class="oddrow player-46-3913176"><td><a href="http://www.espn.com/nba/player/_/id/3913176/brandon-ingram">Brandon Ingram</a></td><td>Day-To-Day</td><td>Mar 24</td></tr><tr class="oddrow player-46-3913176"><td colspan="3"><em>Comment: </em>Ingram (knee) was a full participant in the Lakers' morning shootaround and remains probable for Friday's game against the Timberwolves.</td></tr>
<tr class="stathead"><td colspan="3">Memphis Grizzlies</td></tr><tr class="colhead"><td>NAME</td><td>STATUS</td><td>DATE</td></tr><tr class="oddrow player-46-3206"><td><a href="http://www.espn.com/nba/player/_/id/3206/marc-gasol">Marc Gasol</a></td><td>Day-To-Day</td><td>Mar 27</td></tr><tr class="oddrow player-46-3206"><td colspan="3"><em>Comment: </em>Gasol (foot) has been ruled out for Monday's matchup with the Kings, Michael Wallace of Grizzlies.com reports.</td></tr>
<tr class="evenrow player-46-3206"><td><a href="http://www.espn.com/nba/player/_/id/3206/marc-gasol">Marc Gasol</a></td><td>Day-To-Day</td><td>Mar 27</td></tr><tr class="evenrow player-46-3206"><td colspan="3"><em>Comment: </em>Gasol (foot) is listed as doubtful for Monday's game against the Kings.</td></tr>
<tr class="oddrow player-46-3206"><td><a href="http://www.espn.com/nba/player/_/id/3206/marc-gasol">Marc Gasol</a></td><td>Day-To-Day</td><td>Mar 26</td></tr><tr class="oddrow player-46-3206"><td colspan="3"><em>Comment: </em>Gasol (foot) will not play Sunday night against the Warriors, Rob Fischer of FOX Sports Southest reports.</td></tr>
<tr class="evenrow player-46-3206"><td><a href="http://www.espn.com/nba/player/_/id/3206/marc-gasol">Marc Gasol</a></td><td>Day-To-Day</td><td>Mar 25</td></tr><tr class="evenrow player-46-3206"><td colspan="3"><em>Comment: </em>Gasol is questionable for Sunday's game against the Warriors with a left foot strain.</td></tr>
<tr class="stathead"><td colspan="3">Miami Heat</td></tr><tr class="colhead"><td>NAME</td><td>STATUS</td><td>DATE</td></tr><tr class="oddrow player-46-6628"><td><a href="http://www.espn.com/nba/player/_/id/6628/dion-waiters">Dion Waiters</a></td><td>Out</td><td>Mar 25</td></tr><tr class="oddrow player-46-6628"><td colspan="3"><em>Comment: </em>Waiters (ankle) will not travel with the Heat for the start of their upcoming roadtrip, Shandel Richardson of the South Florida Sun Sentinel reports.</td></tr>
<tr class="evenrow player-46-4262"><td><a href="http://www.espn.com/nba/player/_/id/4262/hassan-whiteside">Hassan Whiteside</a></td><td>Day-To-Day</td><td>Mar 25</td></tr><tr class="evenrow player-46-4262"><td colspan="3"><em>Comment: </em>Whiteside (ankle) said he expects to play Sunday against Boston, Shandel Richardson of the South Florida Sun Sentinel reports.</td></tr>
<tr class="stathead"><td colspan="3">Milwaukee Bucks</td></tr><tr class="colhead"><td>NAME</td><td>STATUS</td><td>DATE</td></tr><tr class="oddrow player-46-6592"><td><a href="http://www.espn.com/nba/player/_/id/6592/john-henson">John Henson</a></td><td>Out</td><td>Mar 25</td></tr><tr class="oddrow player-46-6592"><td colspan="3"><em>Comment: </em>Henson (thumb) is expected to miss roughly a week due to a sprained left thumb, Chris Haynes of ESPN reports.</td></tr>
<tr class="evenrow player-46-6592"><td><a href="http://www.espn.com/nba/player/_/id/6592/john-henson">John Henson</a></td><td>Day-To-Day</td><td>Mar 24</td></tr><tr class="evenrow player-46-6592"><td colspan="3"><em>Comment: </em>Henson (thumb)  will not play Friday against the Hawks.</td></tr>
<tr class="oddrow player-46-6592"><td><a href="http://www.espn.com/nba/player/_/id/6592/john-henson">John Henson</a></td><td>Day-To-Day</td><td>Mar 24</td></tr><tr class="oddrow player-46-6592"><td colspan="3"><em>Comment: </em>Henson (thumb) was scheduled to be reevaluated when the team returned to Milwaukee following Wednesday's win over the Kings, Charles F. Gardner of the Milwaukee Journal Sentinel reports.</td></tr>
<tr class="stathead"><td colspan="3">New Orleans Pelicans</td></tr><tr class="colhead"><td>NAME</td><td>STATUS</td><td>DATE</td></tr><tr class="oddrow player-46-4258"><td><a href="http://www.espn.com/nba/player/_/id/4258/demarcus-cousins">DeMarcus Cousins</a></td><td>Day-To-Day</td><td>Mar 27</td></tr><tr class="oddrow player-46-4258"><td colspan="3"><em>Comment: </em>Cousins (ankle) has been ruled out for Monday's game against the Jazz.</td></tr>
<tr class="evenrow player-46-4258"><td><a href="http://www.espn.com/nba/player/_/id/4258/demarcus-cousins">DeMarcus Cousins</a></td><td>Day-To-Day</td><td>Mar 27</td></tr><tr class="evenrow player-46-4258"><td colspan="3"><em>Comment: </em>Cousins (ankle) is questionable to play in Monday's game against the Jazz, Justin Verrier of ESPN reports.</td></tr>
<tr class="oddrow player-46-4258"><td><a href="http://www.espn.com/nba/player/_/id/4258/demarcus-cousins">DeMarcus Cousins</a></td><td>Day-To-Day</td><td>Mar 26</td></tr><tr class="oddrow player-46-4258"><td colspan="3"><em>Comment: </em>Cousins (ankle) will be sidelined for Sunday's matchup against the Nuggets, Justin Verrier of ESPN reports.</td></tr>
<tr class="evenrow player-46-4258"><td><a href="http://www.espn.com/nba/player/_/id/4258/demarcus-cousins">DeMarcus Cousins</a></td><td>Day-To-Day</td><td>Mar 26</td></tr><tr class="evenrow player-46-4258"><td colspan="3"><em>Comment: </em>Cousins (ankle) didn't participate in the Pelicans' morning shootaround and is considered a game-time decision for Sunday's tilt with the Nuggets, Justin Verrier of ESPN.com reports.</td></tr>
<tr class="oddrow player-46-4258"><td><a href="http://www.espn.com/nba/player/_/id/4258/demarcus-cousins">DeMarcus Cousins</a></td><td>Day-To-Day</td><td>Mar 25</td></tr><tr class="oddrow player-46-4258"><td colspan="3"><em>Comment: </em>day-to-day</td></tr>
<tr class="evenrow player-46-4258"><td><a href="http://www.espn.com/nba/player/_/id/4258/demarcus-cousins">DeMarcus Cousins</a></td><td>Day-To-Day</td><td>Mar 25</td></tr><tr class="evenrow player-46-4258"><td colspan="3"><em>Comment: </em>Cousins is questionable for Sunday's tilt against the Nuggets with a right ankle sprain.</td></tr>
<tr class="oddrow player-46-4253"><td><a href="http://www.espn.com/nba/player/_/id/4253/quincy-pondexter">Quincy Pondexter</a></td><td>Out</td><td>Mar 24</td></tr><tr class="oddrow player-46-4253"><td colspan="3"><em>Comment: </em>Coach Alvin Gentry acknowledged Friday that Pondexter (knee) is unlikely to return for the remainder of the regular season, ESPN.com's Justin Verrier reports. "[Pondexter] will be gearing toward the summer and next year, really," Gentry said.</td></tr>
<tr class="evenrow player-46-3414"><td><a href="http://www.espn.com/nba/player/_/id/3414/omer-asik">Omer Asik</a></td><td>Out</td><td>Mar 24</td></tr><tr class="evenrow player-46-3414"><td colspan="3"><em>Comment: </em>Coach Alvin Gentry acknowledged Friday that Asik (illness) is likely to miss the remainder of the regular season, ESPN.com's Justin Verrier reports. "[Asik] will be gearing toward the summer and next year, really," Gentry said.</td></tr>
<tr class="stathead"><td colspan="3">New York Knicks</td></tr><tr class="colhead"><td>NAME</td><td>STATUS</td><td>DATE</td></tr><tr class="oddrow player-46-6485"><td><a href="http://www.espn.com/nba/player/_/id/6485/lance-thomas">Lance Thomas</a></td><td>Out</td><td>Mar 27</td></tr><tr class="oddrow player-46-6485"><td colspan="3"><em>Comment: </em>out</td></tr>
<tr class="evenrow player-46-6485"><td><a href="http://www.espn.com/nba/player/_/id/6485/lance-thomas">Lance Thomas</a></td><td>Out</td><td>Mar 27</td></tr><tr class="evenrow player-46-6485"><td colspan="3"><em>Comment: </em>Thomas (hip) has been ruled out for Monday's game against the Pistons, Steve Popper of The Record reports.</td></tr>
<tr class="oddrow player-46-6485"><td><a href="http://www.espn.com/nba/player/_/id/6485/lance-thomas">Lance Thomas</a></td><td>Day-To-Day</td><td>Mar 25</td></tr><tr class="oddrow player-46-6485"><td colspan="3"><em>Comment: </em>Thomas (hip) has been ruled out for Saturday's matchup against the Spurs.</td></tr>
<tr class="evenrow player-46-1975"><td><a href="http://www.espn.com/nba/player/_/id/1975/carmelo-anthony">Carmelo Anthony</a></td><td>Day-To-Day</td><td>Mar 25</td></tr><tr class="evenrow player-46-1975"><td colspan="3"><em>Comment: </em>Anthony (knee) will be sidelined for Saturday's matchup against the Spurs.</td></tr>
<tr class="oddrow player-46-6485"><td><a href="http://www.espn.com/nba/player/_/id/6485/lance-thomas">Lance Thomas</a></td><td>Day-To-Day</td><td>Mar 25</td></tr><tr class="oddrow player-46-6485"><td colspan="3"><em>Comment: </em>Thomas (hip) has been upgraded to questionable for Saturday's matchup against the Spurs, Paul Garcia of Project Spurs reports.</td></tr>
<tr class="evenrow player-46-6485"><td><a href="http://www.espn.com/nba/player/_/id/6485/lance-thomas">Lance Thomas</a></td><td>Day-To-Day</td><td>Mar 25</td></tr><tr class="evenrow player-46-6485"><td colspan="3"><em>Comment: </em>Thomas (hip) will not play Saturday against the Spurs, Al Iannazzone of Newsday reports.</td></tr>
<tr class="oddrow player-46-6485"><td><a href="http://www.espn.com/nba/player/_/id/6485/lance-thomas">Lance Thomas</a></td><td>Day-To-Day</td><td>Mar 25</td></tr><tr class="oddrow player-46-6485"><td colspan="3"><em>Comment: </em>Thomas (hip) will not play in Saturday's game against the Spurs, Al Iannazzone of News Day Sports reports.</td></tr>
<tr class="evenrow player-46-6485"><td><a href="http://www.espn.com/nba/player/_/id/6485/lance-thomas">Lance Thomas</a></td><td>Day-To-Day</td><td>Mar 25</td></tr><tr class="evenrow player-46-6485"><td colspan="3"><em>Comment: </em>Thomas (hip) is listed as questionable for Saturday's matchup against the Spurs.</td></tr>
<tr class="oddrow player-46-3456"><td><a href="http://www.espn.com/nba/player/_/id/3456/derrick-rose">Derrick Rose</a></td><td>Day-To-Day</td><td>Mar 25</td></tr><tr class="oddrow player-46-3456"><td colspan="3"><em>Comment: </em>Rose (foot) is considered questionable to play Saturday against the Spurs, Tommy Beer of Basketball Insiders reports.</td></tr>
<tr class="evenrow player-46-1975"><td><a href="http://www.espn.com/nba/player/_/id/1975/carmelo-anthony">Carmelo Anthony</a></td><td>Day-To-Day</td><td>Mar 25</td></tr><tr class="evenrow player-46-1975"><td colspan="3"><em>Comment: </em>Anthony (knee) is listed as questionable for Saturday's game against the Spurs, NBA.com reports.</td></tr>
<tr class="oddrow player-46-3224"><td><a href="http://www.espn.com/nba/player/_/id/3224/joakim-noah">Joakim Noah</a></td><td>Out</td><td>Mar 25</td></tr><tr class="oddrow player-46-3224"><td colspan="3"><em>Comment: </em>Noah will be suspended by the NBA for 20 games for violating its anti-drug program, Adrian Wojnarowski of Yahoo! Sports reports.</td></tr>
<tr class="evenrow player-46-6485"><td><a href="http://www.espn.com/nba/player/_/id/6485/lance-thomas">Lance Thomas</a></td><td>Day-To-Day</td><td>Mar 24</td></tr><tr class="evenrow player-46-6485"><td colspan="3"><em>Comment: </em>day-to-day</td></tr>
<tr class="stathead"><td colspan="3">Orlando Magic</td></tr><tr class="colhead"><td>NAME</td><td>STATUS</td><td>DATE</td></tr><tr class="oddrow player-46-3209"><td><a href="http://www.espn.com/nba/player/_/id/3209/jeff-green">Jeff Green</a></td><td>Out</td><td>Mar 27</td></tr><tr class="oddrow player-46-3209"><td colspan="3"><em>Comment: </em>out</td></tr>
<tr class="evenrow player-46-3209"><td><a href="http://www.espn.com/nba/player/_/id/3209/jeff-green">Jeff Green</a></td><td>Day-To-Day</td><td>Mar 26</td></tr><tr class="evenrow player-46-3209"><td colspan="3"><em>Comment: </em>Green (back) won't travel to Toronto for Monday's game against the Raptors, Josh Robbins of the Orlando Sentinel reports.</td></tr>
<tr class="oddrow player-46-3209"><td><a href="http://www.espn.com/nba/player/_/id/3209/jeff-green">Jeff Green</a></td><td>Day-To-Day</td><td>Mar 24</td></tr><tr class="oddrow player-46-3209"><td colspan="3"><em>Comment: </em>Green (back) has been ruled out of Friday's game against the Pistons, John Denton of OrlandoMagic.com reports.</td></tr>
<tr class="evenrow player-46-3209"><td><a href="http://www.espn.com/nba/player/_/id/3209/jeff-green">Jeff Green</a></td><td>Day-To-Day</td><td>Mar 24</td></tr><tr class="evenrow player-46-3209"><td colspan="3"><em>Comment: </em>Coach Frank Vogel said Green (back) would be a game-time call for Friday's tilt with the Pistons, Josh Robbins of the Orlando Sentinel reports.</td></tr>
<tr class="oddrow player-46-3064290"><td><a href="http://www.espn.com/nba/player/_/id/3064290/aaron-gordon">Aaron Gordon</a></td><td>Day-To-Day</td><td>Mar 24</td></tr><tr class="oddrow player-46-3064290"><td colspan="3"><em>Comment: </em>Gordon is listed as questionable for Friday's game against the Pistons due to a sore left hip, Josh Robbins of the Orlando Sentinel reports.</td></tr>
<tr class="stathead"><td colspan="3">Philadelphia 76ers</td></tr><tr class="colhead"><td>NAME</td><td>STATUS</td><td>DATE</td></tr><tr class="oddrow player-46-3233"><td><a href="http://www.espn.com/nba/player/_/id/3233/tiago-splitter">Tiago Splitter</a></td><td>Out</td><td>Mar 27</td></tr><tr class="oddrow player-46-3233"><td colspan="3"><em>Comment: </em>Splitter (calf) was recalled from the D-League's Delaware 87ers on Monday, Tom Moore of Calkins Media reports</td></tr>
<tr class="evenrow player-46-3135048"><td><a href="http://www.espn.com/nba/player/_/id/3135048/jahlil-okafor">Jahlil Okafor</a></td><td>Day-To-Day</td><td>Mar 26</td></tr><tr class="evenrow player-46-3135048"><td colspan="3"><em>Comment: </em>Okafor (knee) has officially been ruled out for Sunday's matchup with the Pacers, Jessica Camerato of CSN Philadelphia reports.</td></tr>
<tr class="oddrow player-46-3135048"><td><a href="http://www.espn.com/nba/player/_/id/3135048/jahlil-okafor">Jahlil Okafor</a></td><td>Day-To-Day</td><td>Mar 26</td></tr><tr class="oddrow player-46-3135048"><td colspan="3"><em>Comment: </em>Okafor (knee) is unlikely to play in Sunday's game against the Pacers, Keith Pompey of the Philadelphia Inquirer reports.</td></tr>
<tr class="evenrow player-46-3059318"><td><a href="http://www.espn.com/nba/player/_/id/3059318/joel-embiid">Joel Embiid</a></td><td>Out</td><td>Mar 24</td></tr><tr class="evenrow player-46-3059318"><td colspan="3"><em>Comment: </em>Embiid (knee) underwent a successful knee surgery Friday, 76ers' Director of Public Relations Michael Preston reports.</td></tr>
<tr class="oddrow player-46-3135048"><td><a href="http://www.espn.com/nba/player/_/id/3135048/jahlil-okafor">Jahlil Okafor</a></td><td>Day-To-Day</td><td>Mar 24</td></tr><tr class="oddrow player-46-3135048"><td colspan="3"><em>Comment: </em>Okafor (knee) will not play Friday against the Bulls, K.C. Johnson of the Chicago Tribune reports.</td></tr>
<tr class="evenrow player-46-3135048"><td><a href="http://www.espn.com/nba/player/_/id/3135048/jahlil-okafor">Jahlil Okafor</a></td><td>Day-To-Day</td><td>Mar 24</td></tr><tr class="evenrow player-46-3135048"><td colspan="3"><em>Comment: </em>day-to-day</td></tr>
<tr class="oddrow player-46-3135048"><td><a href="http://www.espn.com/nba/player/_/id/3135048/jahlil-okafor">Jahlil Okafor</a></td><td>Day-To-Day</td><td>Mar 24</td></tr><tr class="oddrow player-46-3135048"><td colspan="3"><em>Comment: </em>Coach Brett Brown suggested after the team's morning shootaround that Okafor (knee) is unlikely to play Friday against the Bulls, Keith Pompey of the Philadelphia Inquirer reports. "If you made me guess, he's going to be doubtful," Brown said.</td></tr>
<tr class="stathead"><td colspan="3">Phoenix Suns</td></tr><tr class="colhead"><td>NAME</td><td>STATUS</td><td>DATE</td></tr><tr class="oddrow player-46-4011991"><td><a href="http://www.espn.com/nba/player/_/id/4011991/dragan-bender">Dragan Bender</a></td><td>Out</td><td>Mar 26</td></tr><tr class="oddrow player-46-4011991"><td colspan="3"><em>Comment: </em>Bender (ankle) was able to get up some shots on the court during the Suns' pregame warmup Sunday, Doug Haller of the Arizona Republic reports.</td></tr>
<tr class="evenrow player-46-2166"><td><a href="http://www.espn.com/nba/player/_/id/2166/leandro-barbosa">Leandro Barbosa</a></td><td>Day-To-Day</td><td>Mar 26</td></tr><tr class="evenrow player-46-2166"><td colspan="3"><em>Comment: </em>Barbosa is dealing with a left hamstring injury and will sit out Sunday's game against the Hornets, Doug Haller of the Arizona Republic reports.</td></tr>
<tr class="oddrow player-46-2807"><td><a href="http://www.espn.com/nba/player/_/id/2807/ronnie-price">Ronnie Price</a></td><td>Out</td><td>Mar 26</td></tr><tr class="oddrow player-46-2807"><td colspan="3"><em>Comment: </em>Price (leg) will sit out Sunday's game against the Hornets, Stephanie Ready of FOX Sports Southeast reports.</td></tr>
<tr class="evenrow player-46-2807"><td><a href="http://www.espn.com/nba/player/_/id/2807/ronnie-price">Ronnie Price</a></td><td>Out</td><td>Mar 24</td></tr><tr class="evenrow player-46-2807"><td colspan="3"><em>Comment: </em>Price (leg) will not play in Friday's game against the Celtics, Doug Haller of azcentral.com reports.</td></tr>
<tr class="oddrow player-46-2807"><td><a href="http://www.espn.com/nba/player/_/id/2807/ronnie-price">Ronnie Price</a></td><td>Out</td><td>Mar 24</td></tr><tr class="oddrow player-46-2807"><td colspan="3"><em>Comment: </em>Price (leg) is listed as questionable for Friday's game against the Celtics, but isn't expected to play, John Gambadoro of ArizonaSports.com reports.</td></tr>
<tr class="stathead"><td colspan="3">Sacramento Kings</td></tr><tr class="colhead"><td>NAME</td><td>STATUS</td><td>DATE</td></tr><tr class="oddrow player-46-3983"><td><a href="http://www.espn.com/nba/player/_/id/3983/tyreke-evans">Tyreke Evans</a></td><td>Day-To-Day</td><td>Mar 27</td></tr><tr class="oddrow player-46-3983"><td colspan="3"><em>Comment: </em>Evans (rest) will not play in Monday's game against the Grizzlies, Sean Cunningham of ABC10 Sacramento reports.</td></tr>
<tr class="evenrow player-46-3187"><td><a href="http://www.espn.com/nba/player/_/id/3187/arron-afflalo">Arron Afflalo</a></td><td>Day-To-Day</td><td>Mar 27</td></tr><tr class="evenrow player-46-3187"><td colspan="3"><em>Comment: </em>Afflalo is not with the team for Monday's game against the Grizzlies because of a personal issue, Sean Cunningham of ABC10 (KXTV) Sacramento reports.</td></tr>
<tr class="oddrow player-46-3934663"><td><a href="http://www.espn.com/nba/player/_/id/3934663/malachi-richardson">Malachi Richardson</a></td><td>Out</td><td>Mar 26</td></tr><tr class="oddrow player-46-3934663"><td colspan="3"><em>Comment: </em>Richardson (hamstring) will be shut down for the remainder of the season, Jason Jones of the Sacramento Bee reports.</td></tr>
<tr class="evenrow player-46-4000"><td><a href="http://www.espn.com/nba/player/_/id/4000/ty-lawson">Ty Lawson</a></td><td>Day-To-Day</td><td>Mar 26</td></tr><tr class="evenrow player-46-4000"><td colspan="3"><em>Comment: </em>Lawson won't be available off the bench Sunday against the Rockets due to right knee soreness, Kings sideline reporter Kayte Christensen reports.</td></tr>
<tr class="oddrow player-46-3934663"><td><a href="http://www.espn.com/nba/player/_/id/3934663/malachi-richardson">Malachi Richardson</a></td><td>Out</td><td>Mar 26</td></tr><tr class="oddrow player-46-3934663"><td colspan="3"><em>Comment: </em>Richardson (hamstring) has been ruled out for Sunday's game against the Clippers, James Ham of CSN Bay Area reports.</td></tr>
<tr class="evenrow player-46-3444"><td><a href="http://www.espn.com/nba/player/_/id/3444/kosta-koufos">Kosta Koufos</a></td><td>Day-To-Day</td><td>Mar 26</td></tr><tr class="evenrow player-46-3444"><td colspan="3"><em>Comment: </em>Koufos will sit out Sunday's game against the Clippers for rest, James Ham of CSN Bay Area reports.</td></tr>
<tr class="oddrow player-46-3187"><td><a href="http://www.espn.com/nba/player/_/id/3187/arron-afflalo">Arron Afflalo</a></td><td>Day-To-Day</td><td>Mar 26</td></tr><tr class="oddrow player-46-3187"><td colspan="3"><em>Comment: </em>day-to-day</td></tr>
<tr class="evenrow player-46-3187"><td><a href="http://www.espn.com/nba/player/_/id/3187/arron-afflalo">Arron Afflalo</a></td><td>Day-To-Day</td><td>Mar 26</td></tr><tr class="evenrow player-46-3187"><td colspan="3"><em>Comment: </em>Afflalo will sit out Sunday's game against the Clippers for rest, James Ham of CSN Bay Area reports.</td></tr>
<tr class="oddrow player-46-3934663"><td><a href="http://www.espn.com/nba/player/_/id/3934663/malachi-richardson">Malachi Richardson</a></td><td>Out</td><td>Mar 24</td></tr><tr class="oddrow player-46-3934663"><td colspan="3"><em>Comment: </em>Richardson (hamstring) will not play in Friday's game against the Warriors, James Ham of CSNBayArea.com reports.</td></tr>
<tr class="evenrow player-46-3276"><td><a href="http://www.espn.com/nba/player/_/id/3276/anthony-tolliver">Anthony Tolliver</a></td><td>Day-To-Day</td><td>Mar 24</td></tr><tr class="evenrow player-46-3276"><td colspan="3"><em>Comment: </em>Tolliver will not play in Friday's game against the Warriors in order to rest, James Ham of CSNBayArea.com reports.</td></tr>
<tr class="oddrow player-46-3983"><td><a href="http://www.espn.com/nba/player/_/id/3983/tyreke-evans">Tyreke Evans</a></td><td>Day-To-Day</td><td>Mar 24</td></tr><tr class="oddrow player-46-3983"><td colspan="3"><em>Comment: </em>Evans will not play in Friday's game against the Warriors for resting purposes, James Ham of CSNBayArea.com reports.</td></tr>
<tr class="evenrow player-46-3973"><td><a href="http://www.espn.com/nba/player/_/id/3973/darren-collison">Darren Collison</a></td><td>Day-To-Day</td><td>Mar 24</td></tr><tr class="evenrow player-46-3973"><td colspan="3"><em>Comment: </em>day-to-day</td></tr>
<tr class="oddrow player-46-3973"><td><a href="http://www.espn.com/nba/player/_/id/3973/darren-collison">Darren Collison</a></td><td>Day-To-Day</td><td>Mar 24</td></tr><tr class="oddrow player-46-3973"><td colspan="3"><em>Comment: </em>Collison will take Friday's matchup against the Warriors off in order to rest, James Ham of CSNBayArea.com reports.</td></tr>
<tr class="stathead"><td colspan="3">San Antonio Spurs</td></tr><tr class="colhead"><td>NAME</td><td>STATUS</td><td>DATE</td></tr><tr class="oddrow player-46-3988"><td><a href="http://www.espn.com/nba/player/_/id/3988/danny-green">Danny Green</a></td><td>Day-To-Day</td><td>Mar 25</td></tr><tr class="oddrow player-46-3988"><td colspan="3"><em>Comment: </em>day-to-day</td></tr>
<tr class="evenrow player-46-3988"><td><a href="http://www.espn.com/nba/player/_/id/3988/danny-green">Danny Green</a></td><td>Day-To-Day</td><td>Mar 25</td></tr><tr class="evenrow player-46-3988"><td colspan="3"><em>Comment: </em>Green (rest) will not play Saturday against the Knicks.</td></tr>
<tr class="stathead"><td colspan="3">Toronto Raptors</td></tr><tr class="colhead"><td>NAME</td><td>STATUS</td><td>DATE</td></tr><tr class="oddrow player-46-3970"><td><a href="http://www.espn.com/nba/player/_/id/3970/demarre-carroll">DeMarre Carroll</a></td><td>Day-To-Day</td><td>Mar 27</td></tr><tr class="oddrow player-46-3970"><td colspan="3"><em>Comment: </em>Carroll (back) will not play Monday against Orlando.</td></tr>
<tr class="evenrow player-46-3970"><td><a href="http://www.espn.com/nba/player/_/id/3970/demarre-carroll">DeMarre Carroll</a></td><td>Day-To-Day</td><td>Mar 25</td></tr><tr class="evenrow player-46-3970"><td colspan="3"><em>Comment: </em>Carroll will be sidelined for Saturday's tilt against the Mavericks with a sore lower back.</td></tr>
<tr class="stathead"><td colspan="3">Washington Wizards</td></tr><tr class="colhead"><td>NAME</td><td>STATUS</td><td>DATE</td></tr><tr class="oddrow player-46-3593"><td><a href="http://www.espn.com/nba/player/_/id/3593/bojan-bogdanovic">Bojan Bogdanovic</a></td><td>Day-To-Day</td><td>Mar 25</td></tr><tr class="oddrow player-46-3593"><td colspan="3"><em>Comment: </em>day-to-day</td></tr>
<tr class="evenrow player-46-4237"><td><a href="http://www.espn.com/nba/player/_/id/4237/john-wall">John Wall</a></td><td>Day-To-Day</td><td>Mar 24</td></tr><tr class="evenrow player-46-4237"><td colspan="3"><em>Comment: </em>Coach Scott Brooks said he expects Wall (migraine) to play Friday against the Nets, J. Michael of CSN Mid-Atlantic reports.</td></tr>
<tr class="oddrow player-46-4237"><td><a href="http://www.espn.com/nba/player/_/id/4237/john-wall">John Wall</a></td><td>Day-To-Day</td><td>Mar 24</td></tr><tr class="oddrow player-46-4237"><td colspan="3"><em>Comment: </em>Wall is nursing a migraine and is considered questionable for Friday's game against the Nets, Chase Hughes of CSN Mid-Atlantic reports.</td></tr>
<tr class="evenrow player-46-4237"><td><a href="http://www.espn.com/nba/player/_/id/4237/john-wall">John Wall</a></td><td>Day-To-Day</td><td>Mar 24</td></tr><tr class="evenrow player-46-4237"><td colspan="3"><em>Comment: </em>day-to-day</td></tr>

					</table>
				</div>
				<div class="mod-footer">
					<div class="foot-footer">
						<p>Data Provided By <a target="_blank" href="http://www.rotowire.com/users/ad_jump.asp?id=8"><img src="http://g.espncdn.com/s/flblm/12/images/88x21_RWdotcom_trans.gif"></a></p>
					</div>
				</div>
			</div>
			<!-- begin sponsored links -->
<div id="sponsored" class="mod-container mod-no-footer">
<!-- begin sp links -->
<div style="margin: 10px 0 0 0;">
<div class="bg-opaque mod-outbrain mod-no-border">
<div class="mod-content">
<div class="col-full">
<h4 class="first">SPONSORED HEADLINES</h4>
<div class="OUTBRAIN" data-src="http://www.espn.com/nba/injuries" data-widget-id="AR_24" data-ob-template="espn" ></div></div>
</div>
</div>
<!-- vendor version 2: stats/players/teams-bottom:614w250h vendorId: 09stats614 sport: nba widgetId AR_24 --></div>
<!-- end sp links -->
</div>
<!-- end sponsored links -->

		</div>
		<div class="span-2 last">
			<div class="ad-box">
<!-- begin InContent -->
<div class="gutter">
<!-- gpt ad type: incontent -->
<div class="ad-slot ad-slot-incontent" data-slot-type="incontent" data-slot-kvps="pos=incontent"></div></div>
<!-- end InContent -->
</div>
<!-- pageType: nbainjuries showVendor: true--><div class="bg-opaque mod-outbrain mod-no-border">
<div class="mod-content">
<div class="col-full">
<h4 class="first">SPONSORED HEADLINES</h4>
<div class="OUTBRAIN" data-src="http://www.espn.com/nba/injuries" data-widget-id="AR_21" data-ob-template="espn" ></div></div>
</div>
</div>
<!-- vendor version 2: story-right:304w300h vendorId: 09storyright304 sport: nba widgetId AR_21 -->
		</div>
	</div>
	<div class="clear"></div>
<!-- begin bottom nav -->
<!-- begin bottom nav/sitemap -->
<div class="mod-container mod-jump-menu span-6">
<div class="mod-content">
<!-- NOVALIDATE -->
<ul>
<li>
<ul>
<li><h4><a href="http://www.espn.com/sports/" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_scores">SPORTS</a></h4></li>
<li><a href="http://www.espn.com/nfl/" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_scores_nfl">NFL</a></li>
<li><a href="http://www.espn.com/mlb/" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_scores_mlb">MLB</a></li>
<li><a href="http://www.espn.com/nba/" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_scores_nba">NBA</a></li>
<li><a href="http://www.espn.com/nhl/" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_scores_nhl">NHL</a></li>
<li><a href="http://www.espn.com/college-football/" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_scores_ncf">College Football</a></li>
<li><a href="http://www.espn.com/mens-college-basketball/" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_scores_ncb">College Basketball</a></li>
<li><a href="http://soccernet.espn.com/index" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_scores_soccer">Soccer</a></li>
<li><a href="http://www.espn.com/racing/nascar/" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_scores_nascar">NASCAR</a></li>
</ul>
</li>
<li>
<ul>
<li><h4><a href="http://www.espn.com/sports/" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_sports">MORE</a></h4></li>
<li><a href="http://www.espn.com/racing/" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_sports_racing">Racing</a></li>
<li><a href="http://www.espn.com/golf/" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_sports_golf">Golf</a></li>
<li><a href="http://www.espn.com/tennis/" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_sports_tennis">Tennis</a></li>
<li><a href="http://www.espn.com/boxing/" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_sports_boxing">Boxing</a></li>
<li><a href="http://www.espn.com/mma/" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_sports_mma">MMA</a></li>
<li><a href="http://www.espn.com/college-football/recruiting/" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_sports_recruiting">Recruiting</a></li>
<li><a href="http://www.espn.com/olympics/" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_sports_oly">Olympic Sports</a></li>
<li><a href="http://www.espn.com/horse-racing/" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_sports_horse">Horse Racing</a></li>
</ul>
</li>
<li>
<ul>
<li><h4><a href="http://games.espn.com/frontpage" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_fantasy">FANTASY</a></h4></li>
<li><a href="http://games.espn.com/frontpage/football" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_fantasy_football">Football</a></li>
<li><a href="http://games.espn.com/frontpage/baseball" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_fantasy_baseball">Baseball</a></li>
<li><a href="http://streak.espn.com" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_fantasy_streak">Streak for the Cash</a></li>
</ul>
<ul>
<li><h4><a href="http://www.espn.com/sportsnation/" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_sportsnation">SPORTSNATION</a></h4></li>
<li><a href="http://www.espn.com/sportsnation/polls" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_sportsnation_polls">Polls</a></li>
<li><a href="http://www.espn.com/sportsnation/chats" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_sportsnation_chats">Chats</a></li>
</ul>
</li>
<li>
<ul>
<li><h4><a href="http://www.espn.com/video/">VIDEO</a></h4></li>
<li><a href="http://www.espn.com/video/category?id=2378529" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_video_mostrecent">Most Recent</a></li>
<li><a href="http://search.espn.com/highlights/video/6" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_video_highlights">Highlights</a></li>
</ul>
<ul>
<li><h4>MORE</h4></li>
<li><a href="http://www.espn.com/blog/playbook/sounds/" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_music">Music</a></li>
<li><a href="http://proxy.espn.com/travel/passport/index" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_thelife_passport">Sports Passport</a></li>
<li><a href="http://www.espn.com/travel/sports/calendar/" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_thelife_calendar">Sports Calendar</a></li>
<li><a href="http://www.espn.com/free-online-games/" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_thelife_arcade">Arcade</a></li>
<li><a href="http://www.espn.com/espn/contests/index" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_thelife_contests">Contests</a></li>
</ul>
</li>
<li>
<ul>
<li><h4>TOOLS</h4></li>
<li><a href="http://www.espn.com/espn/news/story?page=contact/index"  name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_tools_contactus">Contact Us</a></li>
<li><a tref="/members/v3_1/index" href="" class="cbOverlay" data-options='{"language":"en","width":"825","height":"425"}'>Member Services</a></li>
<li><a href="http://www.espn.com/espn/corrections" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_tools_corrections">Corrections</a></li>
<li><a href="http://www.espn.com/nfl/lines" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_tools_dailyline">Daily Line</a></li>
<li><a href="http://www.espn.com/espn/news/story?page=rssinfo" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_tools_rss">RSS</a></li>
</ul>
</li>
<li class="last">
<ul>
<li><h4><a href="http://www.espn.com/espntv/espnGuide" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_tv">TV LISTINGS</a></h4></li>
<li><h4><a href="http://www.espn3.com" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_3">WatchESPN</a></h4></li>
<li><h4><a href="http://espnradio.espn.com/espnradio/index" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_radio">RADIO</a></h4></li>
<!--<li><h4><a href="http://espnradio.espn.com/espnradio/podcast/index" name="&amp;lpos=bottomnav2&amp;lid=podcenter">PODCENTER</a></h4></li>-->

<li><h4><a href="http://insider.espn.com/insider/espn-the-magazine/" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_mag">ESPN THE MAGAZINE</a></h4></li>
<li><h4><a href="http://log.espn.com/log?srvc=sz&amp;guid=20547CF2-0B36-404D-81D3-E5339A6CEFBE&amp;drop=0&amp;addata=3374:65:478847:65&amp;a=1&amp;goto=http://espn.teamfanshop.com" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_shop" rel="nofollow">SHOP</a></h4></li>
<li><h4><a href="http://www.sportscenter.com" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_sportscenter">SPORTSCENTER</a></h4></li>
<li><h4><a href="http://www.teamespn.com" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_teamespn">TEAM ESPN</a></h4></li>
<li><h4><a href="http://www.espnfrontrow.com" name="&amp;lpos=bottomnav2&amp;lid=bottomnav2_espnfrontrow">ESPN FRONT ROW</a></h4></li>
</ul>
</li>
</ul>
</div>
</div>
<!-- end bottom nav/sitemap -->
<!-- end bottom nav -->
</div>
<script type="text/javascript">

				// load new edition settings
				var espn = espn || {};
				espn.i18n = espn.i18n || {};
				espn.i18n.lang = "en";
				espn.i18n.siteId = "1";
				espn.i18n.site = "espn";
				espn.i18n.editionKey = "espn-en";
				espn.i18n.personalization = true;
				espn.i18n.country = "us";
				espn.i18n.domain = "www.espn.com";
				espn.i18n.searchUrl = "http://search.espn.com/";
				espn.i18n.hasSearch = true;
				espn.i18n.defaultSearchParams = {};
				espn.i18n.nowFeed = true;
				espn.i18n.temperature = {scale: "fahrenheit", symbol: "&deg; F" };
				espn.i18n.facebook = {appId: "116656161708917", locale: "en_US" };
				espn.i18n.outbrain = {"mobile":{"compliantId":"MB_4","nonCompliantId":"MB_5"},"desktop":{"compliantId":"AR_15","nonCompliantId":"AR_12"},"video":{"mobile":{"compliantId":"MB_6"},"desktop":{"compliantId":"AR_16"}},"recap":{"mobile":{"compliantId":"MB_9"},"desktop":{"compliantId":"AR_30"}}}
				espn.i18n.betting = {show: true, provider: ""};
				espn.i18n.tickets = {"enabled":true,"provider":"VividSeats","baseUrl":"https://www.vividseats.com","callToAction":"Buy on Vivid Seats","trackSection":"vivid"};
				espn.i18n.translations = {};
				espn.i18n.environment = "prod";
				espn.i18n.sportBranding = {"cricket":"logos/logo-uk-cricinfo.png","womenbb":"logos/ESPNcom-powerby-espnw.png","ncaaw":"logos/ESPNcom-powerby-espnw.png","ncw":"logos/ESPNcom-powerby-espnw.png","rugby":"logos/logo-uk-scrum.png","wnba":"logos/ESPNcom-powerby-espnw.png","soccer":"logos/logo-uk-fc.png"};
				espn.i18n.sportToUrl = {"cricket":"http://www.espncricinfo.com","womenbb":"http://espnw.com","ncaaw":"http://espnw.com","ncw":"http://espnw.com","wnba":"http://espnw.com","soccer":"http://www.espnfc.us"};
				espn.i18n.showWatch = true;
				
				espn.i18n.showFCContent = false;
				espn.i18n.showCricInfoContent = false;
				espn.i18n.showInsider = true;
				espn.i18n.indexAutoStart = false;
				espn.i18n.videoAutoStart = {index: false, scoreboard: false };
				espn.i18n.customPrimaryNav = false;
				espn.i18n.isSingleSport = false;
				espn.i18n.sportReplacements = "null";

				espn.i18n.uriRewrites = {"paramKeys":{"toEdition":{},"toEnglish":{}},"pathSegments":{"toEdition":{},"toEnglish":{}},"roots":{"toEdition":{"/horse/":"/horse-racing/","/nascar/":"/racing/nascar/","/ncaa/":"/college-sports/","/ncb/":"/mens-college-basketball/","/ncf/":"/college-football/","/oly/":"/olympics/","/rpm/":"/racing/","/womenbb/":"/womens-basketball/","/flb/":"/fantasy/baseball/","/fba/":"/fantasy/basketball/","/ffl/":"/fantasy/football/","/fhl/":"/fantasy/hockey/"},"toEnglish":{"/oly/summer/gymnastics/":"/oly/summer/gymnastics/","/oly/summer/cycling/":"/oly/summer/cycling/","/racing/nascar/":"/nascar/","/racing/":"/rpm/","/college-football/":"/ncf/","/college-football/rumors":"/college-football/rumors","/mens-college-basketball/":"/ncb/","/mens-college-basketball/rumors":"/mens-college-basketball/rumors","/womens-college-basketball/":"/ncw/","/womens-basketball/":"/womenbb/","/olympics/":"/oly/","/cycling/":"/oly/","/figure-skating/":"/oly/","/college-sports/":"/ncaa/","/gymnastics/":"/oly/","/skiing/":"/oly/","/horse-racing/":"/horse/","/sports/womenbb/":"/womenbb/","/sports/horse/":"/horse/","/sports/endurance/":"/endurance/","/losangeles/":"/los-angeles/","/newyork/":"/new-york/","/espn/onenacion/":"/onenacion/","/fantasy/baseball/":"/flb/","/fantasy/basketball/":"/fba/","/fantasy/football/":"/ffl/","/fantasy/hockey/":"/fhl/"}},"urls":{"toEdition":{},"toEnglish":{}},"paramValues":{"toEdition":{},"toEnglish":{}}};

				try {
					var translations = {"year.mobile.filters":"year","removedFromYourFavorites":"<p>You've removed<\/p><h1>${title}<\/h1>${subTitle}<p>as a suggested favorite<\/p>","preferences.sport_labels.700":"English Premier League","preferences.sport_labels.850":"Tennis","heliumdown":"Login Temporarily Unavailable","newsletters":"Newsletters","no":"No","pageTitle.Scores_%{leagueOrSport}":"${leagueOrSport} Scores","score":"Score","preferences.sport_labels.46":"NBA","video.next.text":"Up Next","message.leaguemanager":"Message LM","favoritesmgmt.manualSortSelected":"You have chosen to manually order how your favorites will appear across ESPN products. At any time, you may return to having ESPN order your favorites by selecting the \"Auto\" option.","onefeed.suggested":"Suggested Favorites","favoritesmgmt.favoriteEntity":"${entity} - Favorite","video.messages.deviceRestricted":"Video is not available for this device.","preferences.sport_labels.41":"NCAAM","manageSettingInPersonalSettings":"You can manage this setting in the future under your <a href=\"#\">Personal Settings<\/a>","hide":"Hide","search":"Search","preferences.sport_labels.1700":"Track and Field","welcomeToESPN":"Welcome to the new ESPN.com","tweet":"Tweet","to_be_determined.abbrev":"TBD","favoriteadded":"Favorite Added","over/under.abbrev":"O/U","preferences.sport_labels.8300":"College Sports","move":"Move","preferences.sport_labels.5501":"FIFA Club World Cup","preferences.sport_labels.3700":"Olympic Sports","preferences.sport_labels.8319":"Snooker","alert":"Alert","preferences.sport_labels.8318":"Darts","favoritesmgmt.confirmHideFavorite":"Hide this from my favorites?","preferences.sport_labels.59":"WNBA","season.mobile.filters":"season","reactivate":"Reactivate","position.abbrev":"POS","favoritesmgmt.alertType":"Alert Type","preferences.sport_labels.54":"NCAAW","createAccount":"Sign Up","viewall":"View All","favoritesmgmt.suggestedHeader":"Suggestions for your location","home":"Home","footerText":"ESPN Internet Ventures. <a href=\"http://disneytermsofuse.com/\" rel=\"nofollow\">Terms of Use<\/a>, <a href=\"http://disneyprivacycenter.com/\" rel=\"nofollow\">Privacy Policy<\/a>, <a href=\"https://disneyprivacycenter.com/notice-to-california-residents/\" rel=\"nofollow\">Your California Privacy Rights<\/a>, <a href=\"https://disneyprivacycenter.com/kids-privacy-policy/english/\">Children's Online Privacy Policy<\/a> and <a href=\"http://preferences-mgr.truste.com/?type=espn&affiliateId=148\">Interest-Based Ads<\/a> are applicable to you. All rights reserved.<\/span><span class=\"link-text-short\">Footer<\/span>","remove":"Remove","preferences.sport_labels.3918":"English FA Cup","preferences.sport_labels.28":"NFL","preferences.sport_labels.3301":"MMA","preferences.sport_labels.1652":"WWE","opponent.abbrev":"OPP","preferences.sport_labels.1650":"Wrestling","preferences.sport_labels.23":"NCAAF","subscribe":"Subscribe","preferences.sport_labels.1200":"Horse Racing","pop-out":"Pop-out","inprogress":"In Progress","connectedfacebook":"Connected to Facebook","manageMy":"Manage my","disableVideoDockingPermanently":"Disable video docking permanently","preferences.sport_labels.3520":"Poker","tc.view.text":"VIEW","suggested":"Suggested","insider.pickcenter.login_message":"To get exclusive PickCenter analysis, you must be an ESPN Insider","preferences.sport_labels.3920":"English Capital One Cup","yes":"Yes","register":"Register","preferences.sport_labels.1000":"Boxing","preferences.sport_labels.33":"CFL","preferences.sport_labels.8098":"X Games","reset.mobile.filters":"reset","favorites.tooManyTeamsToAdd":"Maximum favorite teams limit reached. Please remove at least one team prior to adding additional teams.","Soccer Scores":"Football Scores","confirm":"Confirm","onefeed.scheduleDraft":"Schedule Draft","comments":"Comments","viewAllResultsBySearchTerm":"View all results for '${search_term}'","insiderSubscription":"Insider Subscription","favoritesmgmt.suggestedHeaderReset":"Suggestions","purse":"Purse","undo":"Undo","manageFavoritesSignIn":"To manage favorites please sign-in or create an ESPN account","sports":"Sports","show":"Show","cancel":"Cancel","accountInformation":"Account Information","preferences.sport_labels.2030":"Formula 1","resize":"Resize","nflBye":"Bye","close":"Close","redesignWelcomeText":"We've redesigned the site with some new and exciting features. You have been selected as part of a limited set of fans who get to experience our new site and give it feedback before it launches!","favoritesmgmt.reorderSports":"Reorder Sports","scores":"Scores","thingsHaveChanged":"As you may notice, things have definitely changed","favoritesmgmt.noFavorites":"You have not chosen any favorites yet","preferences.sport_labels.3170":"College Sports","thereAreNoEventsByDisplayNameByDate":"There are no ${displayName} events for ${readableDate}","preferences.sport_labels.800":"Field Hockey","signOut":"Log Out","video.nowPlaying.text":"Now Playing","onefeed.draft":"Draft","filter.mobile.filters":"filter","videoSettings":"Video Settings","favorites.draftingNow":"Drafting Now","noTeamsInFavorites":"No teams in your favorites yet","preferences.sport_labels.90":"NHL","favorites.carousel.espnapp.link":"http://www.espn.com/espn/mobile/products/products/_/id/6857590","enter":"Enter","preferences.sport_labels.2020":"NASCAR","favorites":"Favorites","onefeed.insider.manage":"Manage","video.messages.geoRestricted":"Video is not available in your country.","welcometext":"Welcome","submit.mobile.filters":"submit","favorites.streakLabel":"Current Streak:","email":"Email","pts":"Pts","favoritesmgmt.sportTeam":"${sportLabel} Team","preferences.sport_labels.10":"MLB","onefeed.draftNow":"Draft Now","preferences.sport_labels.200":"Cricket","preferences.sport_labels.1300":"Cycling","preferences.sport_labels.770":"MLS","tc.play.text":"PLAY","preferences.sport_labels.775":"UEFA Champions League","preferences.sport_labels.300":"Rugby","today":"Today","preferences.sport_labels.776":"UEFA Europa League","add":"Add","preferences.sport_labels.8367":"Esports","videoDockingDisabled":"Video docking disabled","connectfacebook":"Connect with Facebook","facebook.conversation.account_policy":"Use a <a href=\"https://www.facebook.com/r.php\" rel=\"nofollow\">Facebook account<\/a> to add a comment, subject to Facebook's <a href=\"https://www.facebook.com/policies/\" rel=\"nofollow\">Terms of Service<\/a> and <a href=\"https://www.facebook.com/about/privacy/\" rel=\"nofollow\">Privacy Policy<\/a>. Your Facebook name, photo & other personal information you make public on Facebook will appear with your comment, and may be used on ESPN's media platforms. <a href=\"http://espn.com/espn/story/_/id/8756098/espn-facebook-comments-faq\">Learn more<\/a>.","earnings":"Earnings","preferences.sport_labels.781":"European Championship","addSomeForQuickAccess":"Add some for quick access","edit":"Edit","preferences.sport_labels.2000":"Racing","preferences.sport_labels.2040":"IndyCar","activateInsider":"Subscribe","favoritesmgmt.noSuggestedFavorites":"Additional Suggested Favorites are not available at this time","favoritesmgmt.confirmRemoveFavorite":"Remove Favorite?","addfavorite":"Add Favorite","preferences.sport_labels.606":"Football","signIn":"Log In","preferences.sport_labels.8374":"Kabaddi","preferences.sport_labels.8373":"Badminton","addFavorites":"Add favorites","preferences.sport_labels.600":"Soccer","preferences.sport_labels.8372":"Chess","preferences.sport_labels.1106":"Golf"};
					if(Object.keys(translations).length > 0) {
						espn.i18n.translations = translations;
					}
				} catch (err) {
					window.console.log('Error in espn.i18n loading translations', err);
				}

				espn.i18n.dateTime = {
					dateFormats: {
						time1: "h:mm A",
						date1: "MMM D, YYYY",
						date2: "M/D/YYYY",
						date3: "MM/DD/YYYY",
						date4: "MMDDYYYY",
						date5: "MMMM Do YYYY",
						date6: "dddd, MMMM Do YYYY",
						date7: "ddd, MMM D YYYY",
						date8: "M/D",
						date9: "ddd",
						date10: "dddd, MMMM Do",
						date11: "ddd, MMMM D",
						date12: "MMMM D, YYYY",
						date13: "dddd, M/D",
						date14: "MMM D",
						date15: "ddd, M/D"

					},

					
					firstDayOfWeek: "Sunday",
					timeZoneBucket: "America/New_York",
					dayNamesShort: ["Su","Mo","Tu","We","Th","Fr","Sa"],dayNamesMedium: ["Sun","Mon","Tues","Wed","Thu","Fri","Sat"],

dayNamesLong: ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],

monthNamesShort: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],

monthNamesLong: ["January","February","March","April","May","June","July","August","September","October","November","December"]
				};
				</script><div id="global-viewport" data-behavior="global_nav_condensed global_nav_full"  class =" interior" >
<nav id="global-nav-mobile" data-loadtype="server"></nav><div class="menu-overlay-primary"></div><div id="header-wrapper" data-behavior="global_header">
<header id="global-header" class="espn-en user-account-management has-search">
    <div class="menu-overlay-secondary"></div>

	<div class="container">
		<a id="global-nav-mobile-trigger" href="#" data-route="false"><span>Menu</span></a><h1><a href="http://www.espn.com"  name="&lpos=sitenavdefault&lid=sitenav_main-logo">ESPN</a></h1>
		<ul class="tools">
			
			<li class="search">
<a href="#" class="icon-font-after icon-search-solid-after" id="global-search-trigger"></a>
<div id="global-search" class="global-search">
	<input type="text" class="search-box" placeholder="Search Sports, Teams or Players..."><input type="submit" class="btn-search">
</div></li>
			
			
			<li class="user" data-behavior="favorites_mgmt"></li>
			
			<li><a href="#" id="global-scoreboard-trigger" data-route="false">Scores</a></li>
		</ul>
		

	</div>

	
<nav id="global-nav" data-loadtype="server">
<ul itemscope="" itemtype="http://www.schema.org/SiteNavigationElement">
<li itemprop="name"><a itemprop="url" href="/nfl/">NFL</a></li><li itemprop="name"><a itemprop="url" href="/nba/">NBA</a></li><li itemprop="name"><a itemprop="url" href="/mlb/">MLB</a></li><li itemprop="name"><a itemprop="url" href="/mens-college-basketball/">NCAAM</a></li><li itemprop="name"><a itemprop="url" href="/college-football/">NCAAF</a></li><li itemprop="name"><a itemprop="url" href="http://www.espnfc.com">Soccer</a></li><li itemprop="name"><a itemprop="url" href="#">&hellip;</a><div><ul class="split"><li itemprop="name"><a itemprop="url" href="/nhl/">NHL</a></li><li itemprop="name"><a itemprop="url" href="/golf/">Golf</a></li><li itemprop="name"><a itemprop="url" href="/tennis/">Tennis</a></li><li itemprop="name"><a itemprop="url" href="/mma/">MMA</a></li><li itemprop="name"><a itemprop="url" href="/wwe/">WWE</a></li><li itemprop="name"><a itemprop="url" href="/boxing/">Boxing</a></li><li itemprop="name"><a itemprop="url" href="/esports/">esports</a></li><li itemprop="name"><a itemprop="url" href="/chalk/">Chalk</a></li><li itemprop="name"><a itemprop="url" href="/analytics/">Analytics</a></li><li itemprop="name"><a itemprop="url" href="/womens-basketball/">NCAAW</a></li><li itemprop="name"><a itemprop="url" href="/womens-basketball/">WNBA</a></li><li itemprop="name"><a itemprop="url" href="/racing/nascar/">NASCAR</a></li><li itemprop="name"><a itemprop="url" href="/jayski/">Jayski </a></li><li itemprop="name"><a itemprop="url" href="/racing/">Racing</a></li><li itemprop="name"><a itemprop="url" href="/horse-racing/">Horse</a></li><li itemprop="name"><a itemprop="url" href="http://www.espn.com/college-sports/football/recruiting/">RN FB</a></li><li itemprop="name"><a itemprop="url" href="http://www.espn.com/college-sports/basketball/recruiting/index">RN BB</a></li><li itemprop="name"><a itemprop="url" href="/college-sports/">NCAA</a></li><li itemprop="name"><a itemprop="url" href="http://www.espn.com/moresports/story/_/page/LittleLeagueWorldSeries/little-league-world-series-espn">LLWS</a></li><li itemprop="name"><a itemprop="url" href="/olympics/">Olympic Sports</a></li><li itemprop="name"><a itemprop="url" href="http://www.espn.com/specialolympics/">Special Olympics</a></li><li itemprop="name"><a itemprop="url" href="http://xgames.com/">X Games</a></li><li itemprop="name"><a itemprop="url" href="http://espncricinfo.com/">Cricket</a></li><li itemprop="name"><a itemprop="url" href="http://www.espnscrum.com/">Rugby</a></li><li itemprop="name"><a itemprop="url" href="/endurance/">Endurance</a></li><li itemprop="name"><a itemprop="url" href="http://www.tsn.ca/cfl">CFL</a></li></ul></div></li><li class="pillar more-espn"><a href="#">More ESPN</a></li><li class="pillar fantasy"><a href="/fantasy/">Fantasy</a></li><li class="pillar listen"><a href="http://www.espn.com/espnradio/index">Listen</a></li><li class="pillar watch"><a href="http://www.espn.com/watchespn/index">Watch</a></li></ul>

</nav>






<nav id="global-nav-secondary" data-loadtype="server" >
<div class="global-nav-container">
<ul class="first-group"><li class="sports" itemprop="name"><span class="positioning"><a href="/nba/"><span class="brand-logo "><img src="http://a.espncdn.com/combiner/i?img=/i/teamlogos/leagues/500/nba.png&w=80&h=80&transparent=true"></span><span class="link-text">NBA</span><span class="link-text-short">NBA</span></a></span></li><li class="sub"><a href="/nba/"  data-breakpoints="desktop,desktop-lg,mobile,tablet" ><span class="link-text">Home</span><span class="link-text-short">Home</span></a></li><li class="sub"><a href="/nba/scoreboard" ><span class="link-text">Scores</span><span class="link-text-short">Scores</span></a></li><li class="sub"><a href="/nba/schedule" ><span class="link-text">Schedule</span><span class="link-text-short">Schedule</span></a></li><li class="sub"><a href="/nba/standings" ><span class="link-text">Standings</span><span class="link-text-short">Standings</span></a></li><li class="sub has-sub"><a href="http://www.espn.com/nba/statistics"  data-mobile="false" ><span class="link-text">Stats</span><span class="link-text-short">Stats</span></a></li><li class="sub"><a href="http://www.espn.com/nba/teams"  data-sportAbbrev="nba" ><span class="link-text">Teams</span><span class="link-text-short">Teams</span></a></li><li class="sub has-sub"><a href="http://www.espn.com/nba/story/_/id/19015770/nba-power-rankings-marc-stein-week-23-rankings" ><span class="link-text">Rankings</span><span class="link-text-short">Rankings</span></a></li><li class="sub has-sub"><a href="/blog/truehoop" ><span class="link-text">TrueHoop</span><span class="link-text-short">TrueHoop</span></a></li><li class="sub pre-loadSubNav"><a href="http://www.espn.com/nba/story/_/page/draft17index/complete-coverage-2017-nba-draft" ><span class="link-text">Draft</span><span class="link-text-short">Draft</span></a></li><li class="sub pre-loadSubNav"><a href="/blog/nba/rumors/insider/" ><span class="link-text">Rumors</span><span class="link-text-short">Rumors</span></a></li><li class="sub pre-loadSubNav"><a href="http://www.espn.com/fantasy/basketball/"  data-mobile="false" ><span class="link-text">Fantasy Basketball</span><span class="link-text-short">Fantasy Basketball</span></a></li><li class="sub pre-loadSubNav"><a href="/blog/marc-stein" ><span class="link-text">Stein Line Live</span><span class="link-text-short">Stein Line Live</span></a></li><li class="sub pre-loadSubNav"><a href="http://www.espn.com/nba/players"  data-mobile="false" ><span class="link-text">Players</span><span class="link-text-short">Players</span></a></li><li class="sub pre-loadSubNav"><a href="http://www.espn.com/nba/transactions"  data-mobile="false" ><span class="link-text">Transactions</span><span class="link-text-short">Transactions</span></a></li><li class="sub pre-loadSubNav"><a href="http://www.espn.com/nba/salaries"  data-mobile="false" ><span class="link-text">Salaries</span><span class="link-text-short">Salaries</span></a></li><li class="sub pre-loadSubNav"><a href="http://www.espn.com/nba/tradeMachine"  data-mobile="false" ><span class="link-text">Trade Machine</span><span class="link-text-short">Trade Machine</span></a></li><li class="sub pre-loadSubNav"><a href="http://www.espn.com/nba/history/awards"  data-mobile="false" ><span class="link-text">Awards</span><span class="link-text-short">Awards</span></a></li><li class="sub pre-loadSubNav"><a href="http://www.espn.com/nba/coaches"  data-mobile="false" ><span class="link-text">Coaches</span><span class="link-text-short">Coaches</span></a></li><li class="sub pre-loadSubNav"><a href="/chalk/" ><span class="link-text">Chalk</span><span class="link-text-short">Chalk</span></a></li><li class="sub insider pre-loadSubNav"><a href="http://insider.espn.com/insider/pickcenter/index?sport=nba"  data-mobile="false" ><span class="link-text">PickCenter</span><span class="link-text-short">PickCenter</span></a></li><li class="sub pre-loadSubNav"><a href="http://www.espn.com/nba/lines"  data-mobile="false" ><span class="link-text">Daily Lines</span><span class="link-text-short">Daily Lines</span></a></li></ul>
	<script type="text/javascript">
		var espn = espn || {};
		espn.nav = espn.nav || {};
		espn.nav.navId = 11929946;
		espn.nav.isFallback = false;

		
		

	</script>
</div>
</nav>

</header></div></div>
<script>
	(function() { 
		var path = window.location.pathname;
		window._espntrack = window._espntrack || [];
		window._espntrack.push( { 'prop58': 'isIndex=' + !!path.match(/\/(index)?$/) } );
	}());
</script></div>
<!-- end content -->
</div>
<!-- end subheader -->
<!-- begin footer -->
<div id="footer" class="container">
<script type="text/javascript">
void 0!==window.jQuery&&jQuery(document).on("track.search",function(n,o,c,e,t){"function"==typeof t&&t.call()});

//<![CDATA[
var link = window.location;
var bugText = '<a href="#" onclick="window.open(\'http://proxy.espn.com/espn/bugs?url=' + escape(link) + '\', \'Bugs\',\'noresizable,noscrollbars,height=400,width=400\');">Report a Bug</a> | ';
//]]>
</script>
<span style="font-weight:bold;">ESPN.com:</span> <a href="http://www.espn.com/espn/news/story?page=help/index">Help</a> | <a href="http://espnmediazone.com/us/" rel="nofollow">Press</a> | <a href="http://www.espncms.com/Advertise-on-ESPN.aspx" rel="nofollow">Advertise on ESPN.com</a> | <a href="http://www.espncms.com" rel="nofollow">Sales Media Kit</a> | <a href="http://preferences.truste.com/2.0/?type=espn&affiliateId=148" onclick="window.open('http://preferences.truste.com/2.0/?type=espn&affiliateId=148','popup','width=986,height=878,scrollbars=yes,resizable=no,toolbar=no,directories=no,location=no,menubar=no,status=no,left=0,top=0'); return false">Interest-Based Ads</a> | <script type="text/javascript">document.write(bugText);</script><a href="http://www.espn.com/espn/corrections">Corrections</a> | <a href="http://www.espn.com/espn/news/story?page=contact/index">Contact Us</a> | <a href="http://www.espn.com/espn/sitemap">Site Map</a> | <a href="http://espncareers.com">Jobs at ESPN</a> <br/>&copy; 2017  ESPN Internet Ventures. <a href="http://disneytermsofuse.com/" rel="nofollow">Terms of Use</a>, <a href="http://disneyprivacycenter.com/" rel="nofollow">Privacy Policy</a>, <a href="https://disneyprivacycenter.com/notice-to-california-residents/" rel="nofollow">Your California Privacy Rights</a>,  <a href="https://disneyprivacycenter.com/kids-privacy-policy/english/">Children's Online Privacy Policy</a> and <a href="http://preferences-mgr.truste.com/?type=espn&affiliateId=148">Interest-Based Ads</a> are applicable to you.  All rights reserved.

<!--
<a href="http://www.espnshop.com/" onClick="location.href('http://log.espn.com/log?srvc=sz&guid=73B641A0-6660-4EFC-B8FF-9DE23E8CD9BD&drop=0&addata=3374:65:478847:65&a=1&goto=http://www.espnshop.com/');return false">Shop</a>
-->

<style>
.videoplayer-show{
z-index:1000;
}
</style></div>
<!-- end footer -->
<!--[if lte IE 9]></div><![endif]-->
</div>
<!-- end bg-elements -->
<script type="text/javascript" src="http://a.espncdn.com/legacy/transitionalHeader/1.0.38/js/nav-external.js"></script>

	<script type="text/javascript">
		(function($) {
			$(document).on('click', 'a[href*="www.vividseats.com"]', function(e) {
				e.preventDefault();
				var url = $(this).attr('href');

				if (! $.parseUri(url).queryKey['wsVar']){
					var wsVar = [],
						sport = espn.sports.getSport(),
						teamName = ("null" && "null".toLowerCase().split(' ').join('-')),
						awayTeamName = (espn && espn.gamepackage && espn.gamepackage.awayTeamName),
						homeTeamName = (espn && espn.gamepackage && espn.gamepackage.homeTeamName),
						pageType = $('body').attr('data-pagetype'),
						device = (espn_ui && espn_ui.Helpers && espn_ui.Helpers.get_media_query_in_use && espn_ui.Helpers.get_media_query_in_use()),
						lang = (espn && espn.i18n && espn.i18n.lang) || 'en',
						normalizedDevice = {
							'desktop-lg': 'desktop',
							'mobile': 'handset'
						},
						sportSlugs = {
					        ncf: "college-football",
					        ncaaf: "college-football",
					        ncb: "mens-college-basketball",
					        ncaab: "mens-college-basketball",
					        ncaam: "mens-college-basketball",
					        ncw: "womens-college-basketball",
					        ncaaw: "womens-college-basketball"
					    };

					if(pageType.indexOf('teamschedule') !== -1) {
						pageType = 'teamschedule';
					} else if(pageType.indexOf('teamstadium') !== -1){
						pageType = 'teamstadium';
					} else if(pageType.indexOf('scoreboard') !== -1) {
						pageType = 'scoreboard';
					}

					if(device) {
						device = normalizedDevice[device] ? normalizedDevice[device] : device;
					}

					if(sport && sport !== 'top') {
						sport = (sportSlugs[sport]) ? sportSlugs[sport] : sport;
						if(pageType) {
							wsVar.push(sport + '~' + pageType);
						} else {
							wsVar.push(sport);
						}
					} else if(pageType) {
						wsVar.push(pageType);
					}

					if((pageType === 'index' || pageType === 'schedule' || pageType === 'scoreboard') && sport) {
						wsVar.push(sport);
					} else if(pageType === 'clubhouse' || pageType === 'teamschedule' || pageType === 'teamstadium') {
						wsVar.push(teamName);
					} else if(pageType === 'gamepackage') {
						wsVar.push(awayTeamName + '~' + homeTeamName);
					}

					if(device) {
						device = normalizedDevice[device] ? normalizedDevice[device] : device;
						wsVar.push(device);
					}


					wsVar.push(lang);
					wsVar = wsVar.join('|');

					url += (url.indexOf("?") !== -1 ? "&" : "?") + 'wsVar=' + wsVar;
				}

				if (! $.parseUri(url).queryKey['wsUser']){
					url += (url.indexOf("?") !== -1 ? "&" : "?") + 'wsUser=717';
				}
				window.open(url);
			});
		})(window.jQuery)
	</script>
	<script type="text/javascript" src="http://a.espncdn.com/legacy/transitionalHeader/1.0.38/js/espn-ads-external.js"></script>



<!-- SiteCatalyst code version: H.21.3 Copyright 1997-2010 Omniture, Inc. http://www.omniture.com -->
<script type="text/javascript">
	var undef = "undefined";
	var s_account="wdgespcom";
	var omniSite = "espn";
	var omniPageName = "espn:nba:injuries";
	
	var insiderStatus = ""
	,anGen = ""
	,anYear = ""
	,anDateString = ""
	,anLoginStatus = ""
	,callOmniture = function(){
		/*-- core story s_omni properties --*/
		if (typeof anCV != "undefined"){anParseLoginBarInfo(anCV);}
		s_omni.pageName="nba:injuries";
		s_omni.server = window.location.host; // Server from the Host
		s_omni.channel = "nba";
		s_omni.prop1 = "espn";
		s_omni.prop4 = "injuries";
		s_omni.prop5 = "nba:injuries";
		s_omni.prop11 = (insiderStatus !== null) ? insiderStatus + ":premium-no" : "premium-no";
		s_omni.prop13 = "automated-general";
		s_omni.prop17 = "en";
		s_omni.hier1 = "nba:injuries";
		s_omni.eVar7 = anYear + ":" + anGen + ":" + anLoginStatus + ":" + s_omni.prop11 + ":";
		s_omni.eVar9 = "en";
		s_omni.eVar13 = "nba:injuries";
		s_omni.eVar19 = "basketball";
		s_omni.eVar21 = "nba";
		
		if (s_omni.prop11=="anonymous:premium-yes" || s_omni.prop11=="insider-no:premium-yes" && typeof s_omni.prop4!="undefined" &&
			(s_omni.prop4=="story" || s_omni.prop4=="blog")) {
			if (typeof s_omni.events != "undefined" && s_omni.events!="") {
				s_omni.events = s_omni.events+",event8";
			} else { 
				s_omni.events="event8";
			}
		}
		
		s_omni.prop38 = "Desktop";
		s_omni.eVar38 = "D=c38";
		
		
		anVersion = "_11apr14_v2_0_r5_";
		
		/************* DO NOT ALTER ANYTHING BELOW THIS LINE ! **************/
		
		var s_code = s_omni.t();
		if(s_code) {
			var sci = new Image(1,1);
			sci.onload = function() {console.log("loaded no append")};
			sci.src = s_code;
		}
		
		
	};	
</script>



<script type="text/javascript">
	if (typeof(s_omni) === 'undefined') {
		(function($) {
			// we want to load the analytics files from the cache if possible - so, let's use full $.ajax() calls
			$.ajax({
				type:'GET',
				url: 'http://a.espncdn.com/combiner/c?js=analytics/sOmni.js,analytics/analytics.js,analytics/externalnielsen.js&v=19',
				dataType: 'script',
				cache: true,
				success: function() {
					var lgSrc;
					if(typeof anCV !== undef && anCV === '' && $('#form-memberarea').length > 0) {
						lgSrc = 'https://r.espn.com/members/util/getUserInfo?cb=runOmnitureIndependently&regType=true';
						$.getScript(lgSrc); // we don't want this to be cached - so we'll use the $.getScript() method
					} else {
						callOmniture();
					}
				}
			});

		}(jQuery));
	}
</script>

<!-- End SiteCatalyst code version: H.21. -->


<!-- Begin comScore Tag -->
<script type="text/javascript">
	if (typeof(jQuery) !== 'undefined') {
		jQuery(function($) {
			if(typeof window._comscore === 'undefined') {
				window._comscore = window._comscore || [];
				window._comscore.push({ c1: "2", c2: "3000005" });
				jQuery.getScript((document.location.protocol == "https:" ? "https://sb" : "http://b") + ".scorecardresearch.com/beacon.js");
			}
		});
	}
</script>
<!-- End comScore Tag -->
		<script>
		var _sf_async_config={
			"uid":22222,
			"domain":"espn.com",
			"pingServer":"pespn.chartbeat.net",
			"useCanonical":true,
			"useSubDomains":false,
			"sections":"nba",
			"authors":"injuries"
		};
		if (typeof(ad_site) !== 'undefined') { _sf_async_config.zone = ad_site; }

		// load both chartbeat_pub and chartbeat_video
		(function () {
			function a() {
				window._sf_endpt = (new Date()).getTime();

				var cbDomain = (("https:"==document.location.protocol)?"https://a248.e.akamai.net/chartbeat.download.akamai.com/102508/":"http://static.chartbeat.com/");

				var c = document.createElement("script");
				c.setAttribute("language","javascript");
				c.setAttribute("type","text/javascript");
				c.setAttribute("src", cbDomain+"js/chartbeat_pub.js");
				document.body.appendChild(c);

				if(typeof StrategyInterface !== 'undefined' && (typeof espn !== 'undefined' && typeof espn.video !== 'undefined')){
					var e = document.createElement("script");
					e.setAttribute("language",	"javascript");
					e.setAttribute("type", "text/javascript");
					e.setAttribute("src", cbDomain+"js/chartbeat_video.js");
					document.body.appendChild(e);
				}
			}
			var b = window.onload;
			window.onload = (typeof window.onload != "function") ? a : function () { b(); a() }
		})();
		//(function(){function a(){window._sf_endpt=(new Date()).getTime();var c=document.createElement("script");c.setAttribute("language","javascript");c.setAttribute("type","text/javascript");c.setAttribute("src",(("https:"==document.location.protocol)?"https://a248.e.akamai.net/chartbeat.download.akamai.com/102508/":"http://static.chartbeat.com/")+"js/chartbeat_pub.js");document.body.appendChild(c)}var b=window.onload;window.onload=(typeof window.onload!="function")?a:function(){b();a()}})();
		</script>
	
<!-- dynamic logic: safecount -->
<script src="http://content.dl-rms.com/rms/mother/508/nodetag.js"></script>

<script type="text/javascript">
   var isInIFrame = (window.location != window.parent.location) ? true : false;
   if(isInIFrame){ if(document.referrer){ if(document.referrer.indexOf(".go.com")==-1){ top.location.href=self.location.href; } } }
</script>

<script type='text/javascript'>
  //<![CDATA[
    (function(){
    window.gravityInsightsParams = {
      'type': 'content',
      'action': '',
      'site_guid': '9a41401e9b7c945344e001ee7f23031e'
    };
    var b,c;b="https:"===document.location.protocol?"https://b-ssl.grvcdn.com/moth-min.js":"http://b.grvcdn.com/moth-min.js";window.grvMakeScript=function(d){var a;a=document.createElement("script");a.type="text/javascript";a.async=!0;a.src=d;return a};c=document.getElementsByTagName("script")[0];b&&c.parentNode.insertBefore(window.grvMakeScript(b),c);})();
  //]]>
</script></body>
</html>
DOC;

        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //执行并获取HTML文档内容
        $html = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);

        return $html;
    }

    /**
     * 解析html，获取球员信息并返回数组
     * @param string $html
     * @return array
     * @throws \Exception
     */
    protected function parseHtml($html)
    {
        $data = [];
        $count = 0;
        $team = '';

        $dom = new Dom;
        $dom->load($html);

        foreach ($dom->find('#my-players-table table tr') as $key => $tr) {
            // 跳过表头等无关信息
            $attr = $tr->getAttribute('class');
            if (strstr($attr, 'stathead')) {
                // 获取球队
                $td = $tr->find('td');
                $team = strip_tags($td->innerHtml);
            } elseif (strstr($attr, 'oddrow') || strstr($attr, 'evenrow')) {
                $td = $tr->find('td');
                if (count($td) === 3) {
                    // 球员信息
                    $data[intval($count / 2)][] = $team;
                    foreach ($td as $value) {
                        $data[intval($count / 2)][] = strip_tags($value->innerHtml);
                    }
                } elseif (count($td) === 1) {
                    // 评论
                    $data[intval($count / 2)][] = strip_tags($td->text);
                    // 此处得到完整等信息，存入数据库

                } else {
                    // 格式发生变化导致数据获取异常
                    throw new \Exception('内容格式错误');
                }
                $count++;
            }
        }
        return $data;
    }
}
