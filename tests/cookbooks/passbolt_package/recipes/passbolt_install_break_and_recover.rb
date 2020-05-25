#
# Cookbook:: docker_test
# Recipe:: passbolt_install_break_and_recover.rb
#
# Copyright:: 2020, The Authors, All Rights Reserved.
#
apt_update

package 'Install mariadb and nginx' do
  package_name ['debconf-utils', 'curl', 'nginx', 'default-mysql-server']
  action :install
end

execute "Break nginx and mysql" do
  command "mv /usr/sbin/nginx /root && mv /usr/bin/mysql /root"
  action :run
end

include_recipe '::passbolt_responses_nginx_mysql'
include_recipe '::passbolt_install_breaks'

execute "Recover nginx and mysql" do
  command "mv /root/nginx /usr/sbin && mv /root/mysql /usr/bin"
  action :run
end

include_recipe '::passbolt_responses_nginx_mysql'
include_recipe '::passbolt_install'
