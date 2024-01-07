@servers(['web' => 'hk.atan.io'])

@task('deploy')
    cd /var/www/dali
    git pull origin main
    npm run build
    supervisorctl restart horizon
@endtask
