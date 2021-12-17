#
# Cookbook:: docker_test
# Recipe:: passbolt_install_mariadb_nginx
#
# Copyright:: 2020, The Authors, All Rights Reserved.
#

# Defaul database engine
database_engine = 'mysql'

if platform_family?('debian')
  apt_update
  package 'Debian: Install mariadb and nginx' do
    package_name ['debconf-utils', 'curl', 'nginx', 'default-mysql-server']
    action :install
  end

  # Use different database service depending on the OS
  if (node['platform'] == 'debian' && node['platform_version'] =='11')
    database_engine = 'mariadb'
  end
  
  execute "Start mysql" do
    command "service #{database_engine} start"
    action  :run
  end
elsif platform_family?('rhel')
  package 'RHEL: Install epel-release repository' do
    package_name ['epel-release']
    action :install
  end
  package 'RHEL: Install mariadb and nginx' do
    package_name ['curl', 'nginx', 'mariadb-server', 'createrepo', 'firewalld']
    action :install
  end
end

include_recipe '::passbolt_responses_nginx_mysql'
include_recipe '::passbolt_install'
