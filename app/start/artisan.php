<?php

/*
|--------------------------------------------------------------------------
| Register The Artisan Commands
|--------------------------------------------------------------------------
|
| Each available Artisan command must be registered with the console so
| that it is available to be called. We'll register every command so
| the console gets access to each of the command object instances.
|
*/

Artisan::add(new ImportCommand);
Artisan::add(new RankCommand);
Artisan::add(new SearchCommand);
Artisan::add(new SlugCommand);
Artisan::add(new EntitiesCommand);
Artisan::add(new WarmupCommand);
Artisan::add(new CacheCommand);
Artisan::add(new ImagesCommand);
Artisan::add(new CoordUpdateCommand);
Artisan::add(new YipitCommand);
Artisan::add(new EdmundsCommand);
Artisan::add(new EmailsCommand);
Artisan::add(new TrialCommand);
Artisan::add(new ProjectTagCommand);
Artisan::add(new SoctCommand);
Artisan::add(new SitemapCommand);
Artisan::add(new InventoryImportCommand);
Artisan::add(new SeoContentCommand);
Artisan::add(new WaveImportCommand);
Artisan::add(new LeaseCommand);
Artisan::add(new ContestCommand);
Artisan::add(new CleanupCommand);
