# TYPO3 extension patchcache

## What is does

One patch for the TYPO3 Cache is inside of this extension.

The cHash paramter has only be added if the configuration useCacheHash=1 in all versions of TYPO3 before 9. Without this patch it is not possible any more to not use the cache. This however makes it impossible to use an Ajax call, because the page will be fetched only from the cache, even when the paramter no_cache=1 is provided.


## alternative solution in TYPO3 9

Add the exceptions for the cHash calculation in the Install Tool, if the eID parameter of an Ajax call resulted in a wrong page coming from the cache. Then the cHash can be at the end of the link and it will not result in this error:

    Core: Exception handler (WEB): Uncaught TYPO3 Exception: #1518472189: Request parameters could not be validated (&cHash comparison failed)

  $GLOBALS['TYPO3_CONF_VARS']['FE']['cacheHash']['excludedParameters'] = ...,eID

Please send me a notification if there is any other solution.

[Issue #90973](https://forge.typo3.org/issues/90973?issue_count=8&issue_position=1&next_issue_id=88767)


