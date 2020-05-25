#
# Cookbook:: docker_test
# Recipe:: passbolt_responses_nginx_mysql.rb
#
# Copyright:: 2020, The Authors, All Rights Reserved.
#

# Default values:
# mysql-config: false
# db admin: root
# db admin-pass: empty
# db user: passboltadmin
# db pass: empty
# db name: passboltdb
# nginx-config: false
# nginx-ssl: none
# nginx-domain: empty (postinst sets _)

execute "Provide responses to customize nginx and mysql" do
  command "printf 'passbolt-#{node['passbolt_flavour']}-server passbolt/mysql-configuration select true' | debconf-set-selections ; \
	  printf 'passbolt-#{node['passbolt_flavour']}-server passbolt/mysql-passbolt-password password hawhawhaw' | debconf-set-selections ; \
	  printf 'passbolt-#{node['passbolt_flavour']}-server passbolt/nginx-configuration select true' | debconf-set-selections ; \
	  printf 'passbolt-#{node['passbolt_flavour']}-server passbolt/nginx-configuration-two-choices select none' | debconf-set-selections ; \
    printf 'passbolt-#{node['passbolt_flavour']}-server passbolt/nginx-domain string 127.0.0.1' | debconf-set-selections"
  action :run
end
