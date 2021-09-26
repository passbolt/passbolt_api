#
# Cookbook:: docker_test
# Recipe:: passbolt_install_mariadb_nginx
#
# Copyright:: 2020, The Authors, All Rights Reserved.
#

# Defaul database engine
database_engine = 'mysql'
apt_update

package 'mariadb-server' do
  package_name [ 'debconf-utils', 'curl', 'default-mysql-server', 'nginx' ]
  action       :install
end

# Use different database service depending on the OS
if (node['platform'] == 'debian' && node['platform_version'] =='11')
  database_engine = 'mariadb'
end

execute "Start mysql" do
  command "service #{database_engine} start"
  action  :run
end


include_recipe '::passbolt_responses_nginx_mysql'
include_recipe '::passbolt_install'
