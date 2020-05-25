#
# Cookbook:: docker_test
# Recipe:: passbolt_install_mariadb_nginx
#
# Copyright:: 2020, The Authors, All Rights Reserved.
#
apt_update

package 'mariadb-server' do
  package_name [ 'debconf-utils', 'curl', 'mariadb-server', 'nginx' ]
  action       :install
end

execute "Start mysql" do
  command "service mysql start"
  action  :run
end

include_recipe '::passbolt_responses_nginx_mysql'
include_recipe '::passbolt_install'
