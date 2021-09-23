#
# Cookbook:: docker_test
# Recipe:: passbolt_install_mariadb_nginx
#
# Copyright:: 2020, The Authors, All Rights Reserved.
#
database_engine = ''
apt_update

package 'mariadb-server' do
  package_name [ 'debconf-utils', 'curl', 'mariadb-server', 'nginx' ]
  action       :install
end

case node['platform']
when 'debian'
  if node['platform_version'] =='10'
    database_engine = 'mysql'
  elsif node['platform_version'] =='11'
    database_engine = 'mariadb'
  end

when 'ubuntu'
  database_engine = 'mysql'
end

execute "Start mysql" do
  command "service #{database_engine} start"
  action  :run
end


include_recipe '::passbolt_responses_nginx_mysql'
include_recipe '::passbolt_install'
