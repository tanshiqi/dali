@servers(['web' => 'hk.atan.io'])

@task('update')
    cd /var/www/dali
    git pull origin main
    npm run build
    supervisorctl restart horizon
@endtask
