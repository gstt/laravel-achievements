<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Table naming
    |--------------------------------------------------------------------------
    |
    | Allows changing the default names for the Achievement tables.
    |
    | By default, there are two tables that store achievement data:
    |
    | * achievement_details stores details related to the achievement itself:
    |   -> name, description, amount of points to unlock.
    | * achievement_progress stores details related to a specific unlock or progress.
        -> it contains ids for the achiever and achievement, as well as the amount
           of points obtained and when the achievement was unlocked.
    |
    | This setting allows changing the default names of these tables.
    | Please note that changing this before migrating will also change preemptively
    | the name of the created tables on the database.
    */
    'table_names' => [
        'details' => 'achievement_details',
        'progress' => 'achievement_progress'
    ],

    /*
    |--------------------------------------------------------------------------
    | Locked achievement sync
    |--------------------------------------------------------------------------
    |
    | Controls the behavior of how locked achievements will be handled.
    |
    | Achievements are only stored on the achievement_progress table whenever
    | they are made progress or unlocked. Therefore, by default there is no
    | "locked achievement" storage.
    |
    | When set to FALSE, this will not change how the relationship works.
    | achievements() on the Achiever trait WILL NOT RETURN LOCKED ACHIEVEMENTS,
    | only returning records on the achievement_progress table. The locked()
    | method will act as a simple query fetching all records that exist in
    | achievement_details and do not have equivalent records on
    | achievement_progress.
    |
    | When set to TRUE, any calls to the achievements() relationship will first
    | fetch locked achievements and then add them to the achievement_progress
    | table with progress 0. Therefore, the achievements() relationship WILL
    | RETURN LOCKED ACHIEVEMENTS, and the locked() method will act as a derived
    | query from achievements().
    |
    */
    'locked_sync' => true,

    /*
    |--------------------------------------------------------------------------
    | Automatic achievement sync
    |--------------------------------------------------------------------------
    |
    | Automatically syncs achievement data from source code to database tables.
    |
    | When set to true, all calls to Achievement classes will attempt to sync
    | data from the source code to the AchievementDetails tables.
    | This will keep your database in sync, but may also increase the amount
    | of database calls.
    */
    'auto_sync' => false
];
