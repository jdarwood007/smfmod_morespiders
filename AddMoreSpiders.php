<?php
// Manual Install?
// If the user is manually installing allow them to upload install.php 
// providing its in the same directory as SMF, allow the install to proceed, 
if (!defined('SMF'))
	include_once('SSI.php');

// Globals
global $db_prefix, $context;

// If we're uninstalling, we can get out of here.
if (!empty($context['uninstalling']))
	return;

$addSpider = array(
	// MAJOR spiders							 			Website												Description
	array('Ask', 'Teoma'),									// https://www.ask.com								Spider for Ask Search Engine
	array('Baidu', 'Baiduspider'),							// https://www.baidu.com							Spider for Chinese search engine
	array('Bing', 'bingbot'),								// https://www.bing.com								Spider for Microsoft Bing
	array('GigaBot', 'Gigabot'),							// https://www.gigablast.com						Another heavily travelled spider
	array('DuckDuckGo', 'DuckDuckBot'),						// https://www.duckduckgo.com						Another heavily travelled spider
	array('Google-AdSense', 'Mediapartners-Google'),		// https://www.google.com							Spider related to Adsense/Adwords
	array('Google-Adwords', 'AdsBot-Google'),				// https://www.google.com							Spider related to Adwords
	array('Google-SA', 'gsa-crawler'),						// https://www.google.com							Google Search Appliance Spider
	array('Google-Image', 'Googlebot-Image'),				// https://www.google.com							Spider for google image search
	array('InternetArchive', 'ia_archiver-web.archive.org'),// https://www.archive.org							Way back When machine Spider
	array('Alexa', 'ia_archiver'),							// https://www.alexa.com							*Must be detected after Internet Archive
	array('Omgili', 'omgilibot'),							// https://www.omgili.com							Extremely aggressive Messageboard/forum Spider
	array('Speedy Spider', 'Speedy Spider'),				// https://www.entireweb.com						Entire web spider
	array('Yahoo', 'yahoo'),								// https://www.yahoo.com							For Yahoo Publisher Network  (a variety in use)
	array('Yahoo JP', 'Y!J'),								// https://www.yahoo.co.jp							Spider for Yahoo Japan
	array('Facebook', 'facebot'),							// http://www.facebook.com/externalhit_uatext.php	Spider for Facebook from external link crawling
	array('Facebook External hit', 'facebookexternalhit'),	// http://www.facebook.com/externalhit_uatext.php	Spider for Facebook from external link crawling
	
	// Checkers/Testers/Robots						 		Website												Description
	array('DeadLinksChecker', 'link validator'),			// https://www.dead-links.com/						Checks your site for dead/bad links
	array('W3C Validator', 'W3C_Validator'),				// https://validator.w3.org							Checks standards validity of any html/xhtml page
	array('W3C CSSValidator', 'W3C_CSS_Validator'),			// https://jigsaw.w3.org/css-validator/				Checks standards validity of css stylesheets
	array('W3C FeedValidator', 'FeedValidator'),			// https://validator.w3.org/feed/ 					Checks standards validity of atom/rss feeds
	array('W3C LinkChecker', 'W3C-checklink'),				// https://validator.w3.org/checklink				Checks links on any html/xhtml page are valid
	array('W3C mobileOK', 'W3C-mobileOK'),					// https://www.w3.org/2006/07/mobileok-ddc			Checks page for how good it is for mobiles
	array('W3C P3PValidator', 'P3P Validator'),				// https://www.w3.org/P3P/validator.html			Checks something??
			
	// Feed readers								 			Website												Description
	array('Bloglines', 'Bloglines'),						// https://www.bloglines.com						Spider for blog/rich web content (owned by Ask)
	array('Feedburner', 'Feedburner'),						// https://www.feedburner.com						Another RSS feed reader
	
	// Website Thumbnail/Snapshot/Thumbshot takers		 	Website												Description
	array('SnapBot', 'Snapbot'),							// https://www.snap.com								Shapshots provider
	array('Picsearch', 'psbot'),							// https://www.picsearch.com						Picture/Image Search Engine
	array('Websnapr', 'Websnapr'),							// https://www.websnapr.com							Snapshot/site screenshot taker
			
	// More MINOR Spiders/Robots					 		Website												Description
	array('AllTheWeb', 'FAST-WebCrawler'), 					// https://www.alltheweb.com						Spider for alltheweb (now owned by Yahoo)
	array('Altavista', 'Scooter'),							// https://www.altavista.com						Another Major Search Engine spider
	array('Asterias', 'asterias'),							// https://www.aol.com								Media Spider
	array('AOL', 'AOLBuild'),								// https://www.aol.com								Media Spider
	array('192bot', '192.comAgent'),						// https://www.192.com								Spider to index for 192.com
	array('AbachoBot', 'ABACHOBot'),						// https://www.abacho.com							Spider for multi language search engine/translator
	array('Abdcatos', 'abcdatos'),							// https://www.abcdatos.com/botlink/				Spider for Italian Search Engine
	array('Acoon', 'Acoon'),								// https://www.acoon.de								Spider for small search engine
	array('Accoona', 'Accoona'),							// https://www.accoona.com							Spider for Accoona
	array('BecomeBot', 'BecomeBot'),						// https://www.become.com							Shopping/Products type search engine
	array('BlogRefsBot', 'BlogRefsBot'),					// https://www.blogrefs.com/about/bloggers			Blogs related spider
	array('Daumoa', 'Daumoa'),								// https://ws.daum.net/aboutkr.html					South Korean Search Engine Spider
	array('DuckDuckBot', 'DuckDuckBot'),					// https://duckduckgo.com/duckduckbot.html			Spider for small search engine
	array('Exabot', 'Exabot'),								// https://www.exalead.com							Spider for small search engine
	array('Furl', 'Furlbot'),								// https://www.furl.net								Spider for Furl social bookmarking site
	array('FyperSpider', 'FyberSpider'),					// https://www.fybersearch.com						Spider for Small Search Engine
	array('Geona', 'GeonaBot'),								// https://www.geona.com							Spider for another small search engine
	array('GirafaBot', 'Girafabot'),						// https://www.girafa.com/							Thumbshot provider
	array('GoSeeBot', 'GoSeeBot'),							// https://www.gosee.com/bot.html					Spider for small search engine
	array('Ichiro', 'ichiro'),								// https://help.goo.ne.jp/door/crawler.html			Spider for Japanese search engine
	array('LapozzBot', 'LapozzBot'),						// https://www.lapozz.hu				 			Spider for Hungarian search engine
	array('Looksmart', 'WISENutbot'),						// https://www.looksmart.com						Spider related to advertising
	array('Lycos', 'Lycos_Spider'),							// https://www.lycos.com							Spider for search engine
	array('Majestic12', 'MJ12bot/v2'),						// https://www.majestic12.co.uk/					Distributed Search Engine Project
	array('MLBot', 'MLBot'),								// https://www.metadatalabs.com/					Media indexing spider
	array('MSRBOT', 'msrbot'),								// https://research.microsoft.com/research/sv/mrbot/  	Microsoft Research bot
	array('MSR-ISRCCrawler', 'MSR-ISRCCrawler'),			// https://www.microsoft.com/research/	  			Another Microsoft Research bot
	array('Naver', 'NaverBot'),								// https://www.naver.com							South Korean Search Engine Spider
	array('Naver', 'Yeti'),									// https://www.naver.com							Another NaverBot for the South Korean Search Engine
	array('NoxTrumBot', 'noxtrumbot'),						// https://www.noxtrum.com							Spider for Spanish search engine
	array('OmniExplorer', 'OmniExplorer_Bot'),				// https://www.omni-explorer.com/					Spider
	array('OnetSzukaj', 'OnetSzukaj'),						// https://szukaj.onet.pl							Polish Search Engine Spider
	array('ScrubTheWeb', 'Scrubby'),						// https://www.scrubtheweb.com						Spider for Scrub the web
	array('SearchSight', 'SearchSight'),					// https://www.searchsite.com						Another search engine
	array('Seeqpod', 'Seeqpod'),							// https://www.seeqpod.com							Spider for search engine (the google for mp3 files)
	array('Shablast', 'ShablastBot'),						// https://www.shablast.com							Spider for a small search engine
	array('SitiDiBot', 'SitiDiBot'),						// https://www.sitidi.net							Spider for italian Sitidi search engine
	array('Slider', 'silk/1.0'),							// https://www.slider.com							Spider for Slider, but it only spiders DMOZ entries
	array('Sogou', 'Sogou'),								// https://www.sogou.com							Spider for Chinese search engine
	array('Sosospider', 'Sosospider'),						// https://help.soso.com/webspider.htm				Non-english search engine
	array('StackRambler', 'StackRambler'),					// https://www.rambler.ru/doc/robots.shtml			Spider for Russian portal/search engine
	array('SurveyBot', 'SurveyBot'),						// https://www.domaintools.com						Probe for website statistics (WhoIs  Source)
	array('Touche', 'Touche'),								// https://www.touche.com.ve						Another small search engine
	array('Walhello', 'appie'),								// https://www.wahello.com/							Spider for wahello
	array('WebAlta', 'WebAlta'), 							// https://www.webalta.net							Russian Search Engine
	array('Wisponbot', 'wisponbot'), 						// https://www.wispon.com							Korean Search Engine
	array('YacyBot', 'yacybot'),							// https://www.yacy.com			 					Crawler for distributed search engine
	array('YodaoBot', 'YodaoBot'),							// https://www.yodao.com							Spider for Chinese Search Engine
			
	// Google-Wanna-Be's - Spiders/Robots for Startups		 Website											Description	
	array('Charlotte', 'Charlotte'),						// https://www.searchme.com/support/ 				Spider for new search engine (in beta)
	array('DiscoBot', 'DiscoBot'),							// https://discoveryengine.com/discobot.html		Spider for new search engine startup
	array('EnaBot', 'EnaBot'),								// https://www.enaball.com/crawler.html				Experimental new spider
	array('Gaisbot', 'Gaisbot'),							// https://gais.cs.ccu.edu.tw/robot.php				Spider for search engine startup
	array('Kalooga', 'kalooga'),							// https://www.kalooga.com							Spider for new media search engine (in beta)
	array('ScoutJet', 'ScoutJet'),							// https://www.scoutjet.com/						Spider for new search engine (by the DMOZ founders)
	array('TinEye', 'TinEye'),								// https://tineye.com/crawler.html					Spider for search engine startup
	array('Twiceler', 'twiceler'),							// https://www.cuill.com/twiceler/robot.html		Experimental Spider, (aggressive)
	
	// Software								 				Website												Description
	array('GSiteCrawler', 'GSiteCrawler'),					// https://www.gsitecrawler.com/					Windows Based Sitemap Generator Software
	array('HTTrack', 'HTTrack'),							// https://www.httrack.com							HTTrack Website Copier - Offline Browser
	array('Wget', 'Wget'),									// https://www.gnu.org/software/wget/				GNU software to retrieve files
	// Reason for detecting these: They can be very intensive. So seeing them in use, enables you to block if necessary.
);

// Correction from v1.0
// Alexa/InternetArchive use similar 
$smcFunc['db_query']('', '
	DELETE FROM {db_prefix}spiders
	WHERE spider_name = {string:spider_name}
		AND user_agent = {string:user_agent}
	',
	array(
		'spider_name' => 'InternetArchive',
		'user_agent' => 'ia_archiver',
	)
);

// Grab all the existing spiders to match against
$request = $smcFunc['db_query']('', '
	SELECT user_agent
	FROM {db_prefix}spiders',
	array()
);

$knownspiders = array();
if ($smcFunc['db_num_rows']($request) != 0)
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$knownspiders[] = $row['user_agent'];
$smcFunc['db_free_result']($request);

// Now go through spider in the mo
foreach ($addSpider as $spider)
	// If doesn't already exist in the table, then add it
	if (!in_array($spider[1], $knownspiders))
		// Now add each spider
		$smcFunc['db_insert']('ignore',
			'{db_prefix}spiders',
			array('spider_name' => 'string', 'user_agent' => 'string', 'ip_info' => 'string'),
			array($spider[0], $spider[1], ''),
			array('spider_name', 'user_agent', 'ip_info')
		);

//Unset everything
unset($knownspiders, $addSpider, $spider);

// If we're using SSI, tell them we're done
if (SMF == 'SSI')
	echo 'Database changes are complete!';