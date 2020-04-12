<?php
declare(strict_types = 1);
namespace Bugfix\Patchcache\Frontend\Typolink;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use Psr\Http\Message\UriInterface;
use TYPO3\CMS\Core\Http\Uri;
use TYPO3\CMS\Core\Routing\InvalidRouteArgumentsException;
use TYPO3\CMS\Core\Routing\RouterInterface;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Site\Entity\SiteLanguage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

use \TYPO3\CMS\Frontend\Typolink\UnableToLinkException;

/**
 * Builds a TypoLink to a certain page
 */
class PageLinkBuilder extends \TYPO3\CMS\Frontend\Typolink\PageLinkBuilder
{
    /**
     * Create a UriInterface object when linking to a page with a site configuration
     *
     * @param array $page
     * @param Site $siteOfTargetPage
     * @param array $queryParameters
     * @param string $fragment
     * @param array $conf
     * @return UriInterface
     * @throws UnableToLinkException
     */
    protected function generateUrlForPageWithSiteConfiguration(array $page, Site $siteOfTargetPage, array $queryParameters, string $fragment, array $conf): UriInterface
    {
        $currentSite = $this->getCurrentSite();
        $currentSiteLanguage = $this->getCurrentSiteLanguage();
        // Happens when currently on a pseudo-site configuration
        // We assume to use the default language then
        if ($currentSite && !($currentSiteLanguage instanceof SiteLanguage)) {
            $currentSiteLanguage = $currentSite->getDefaultLanguage();
        }

        $siteLanguageOfTargetPage = $this->getSiteLanguageOfTargetPage($siteOfTargetPage, (string)($conf['language'] ?? 'current'));

        // By default, it is assumed to ab an internal link or current domain's linking scheme should be used
        // Use the config option to override this.
        $useAbsoluteUrl = $conf['forceAbsoluteUrl'] ?? false;
        // Use the cHash parameter
        $useCacheHash = $conf['useCacheHash'] ?? false;
        // Check if the current page equal to the site of the target page, now only set the absolute URL
        // Always generate absolute URLs if no current site is set
        if (
            !$currentSite
            || $currentSite->getRootPageId() !== $siteOfTargetPage->getRootPageId()
            || $siteLanguageOfTargetPage->getBase()->getHost() !== $currentSiteLanguage->getBase()->getHost()) {
            $useAbsoluteUrl = true;
        }

        $targetPageId = (int)($page['l10n_parent'] > 0 ? $page['l10n_parent'] : $page['uid']);
        $queryParameters['_language'] = $siteLanguageOfTargetPage;

        if ($conf['no_cache']) {
            $queryParameters['no_cache'] = 1;
        }

        if ($fragment
            && $useAbsoluteUrl === false
            && $currentSiteLanguage === $siteLanguageOfTargetPage
            && $targetPageId === (int)$GLOBALS['TSFE']->id
            && (empty($conf['addQueryString']) || !isset($conf['addQueryString.']))
            && !$GLOBALS['TSFE']->config['config']['baseURL']
            && count($queryParameters) === 1 // _language is always set
            ) {
            $uri = (new Uri())->withFragment($fragment);
        } else {
            try {
                $uri = $siteOfTargetPage->getRouter()->generateUriPatched(
                    $targetPageId,
                    $queryParameters,
                    $fragment,
                    $useAbsoluteUrl ? RouterInterface::ABSOLUTE_URL : RouterInterface::ABSOLUTE_PATH,
                    $useCacheHash
                );
            } catch (InvalidRouteArgumentsException $e) {
                throw new UnableToLinkException('The target page could not be linked. Error: ' . $e->getMessage(), 1535472406);
            }
            // Override scheme, but only if the site does not define a scheme yet AND the site defines a domain/host
            if ($useAbsoluteUrl && !$uri->getScheme() && $uri->getHost()) {
                $scheme = $conf['forceAbsoluteUrl.']['scheme'] ?? 'https';
                $uri = $uri->withScheme($scheme);
            }
        }

        return $uri;
    }
}
