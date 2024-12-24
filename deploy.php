<?php

namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'sekatsuku-api');

// Project repository
set('repository', 'git@github.com:linnoedge/sekatsuku-be.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false);

set('ssh_multiplexing', false);  #(if Windows  uncomment this)

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);

set('allow_anonymous_stats', false);

set('release_name', function () {
    date_default_timezone_set('Asia/Tokyo');
    return date('YmdHis');
});

set('keep_releases', 3); // Optional: Adjust the number of releases to keep
set('rsync_excludes', [
    '.env', // Exclude the .env file
]);

// Hosts
host('staging')
    ->set('hostname', '52.193.192.11')
    ->setRemoteUser('ubuntu')
    ->set('branch', 'develop')
    ->set('identity_file', 'D:/staging-sekatsuku.pem')
    ->set('deploy_path', '/var/www/sekatsuku/{{application}}');

host('production')
    ->set('hostname', '13.230.145.228')
    ->setRemoteUser('ubuntu')
    ->set('branch', 'production')
    ->set('identity_file', 'D:/prod-sekatsuku.pem')
    ->set('deploy_path', '/var/www/sekatsuku/{{application}}');

// Tasks
task('build', function () {
    run('cd {{release_path}} && build');
});
task('artisan:migrate')->disable();

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.
// before('deploy:symlink', 'artisan:migrate');
after('deploy:symlink', 'artisan:queue:restart');
