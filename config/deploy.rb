set :application, 'bruges-ballooning'
set :repo_url, 'git@github.com:tombroucke/capistrano-test.git'
set :tmp_dir, '/data/sites/web/otomatiesbe/tmp'
set :ssh_options, {:forward_agent => true}

# Hardcode branch to always be master
# This could be overridden in a stage config file
set :branch, :master

# Use :debug for more verbose output when troubleshooting
set :log_level, :info

namespace :deploy do
  desc 'Write the current version to www/revision.txt'
  task :write_revision_to_file do
    on roles(:app) do
      within repo_path do
        execute "echo #{fetch(:release_timestamp)} #{fetch(:current_revision)} > #{fetch(:release_path)}/www/revision.txt"
      end
    end
  end
end

after 'deploy:published', 'deploy:write_revision_to_file'

# Reload PHP
namespace :deploy do
  desc 'Restart application'
  task :restart do
    on roles(:app) do
      # Your restart mechanism here, for example:
      # execute :service, :nginx, :reload
      # execute "sudo service httpd restart"
      within release_path do
      print "Reload PHP & waiting 5 seconds\n"
      execute "reloadPHP.sh"
      sleep 5
      end
    end
  end
end
after 'deploy:published', 'deploy:restart'
