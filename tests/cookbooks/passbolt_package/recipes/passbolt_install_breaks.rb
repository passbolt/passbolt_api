#
# Cookbook:: docker_test
# Recipe:: passbolt_install_breaks
#
# Copyright:: 2020, The Authors, All Rights Reserved.
#

apt_update

execute "Install passbolt, expect to break, display output" do
  command "VERBOSE=1 DEBIAN_FRONTEND=noninteractive apt-get install -y #{node['parameters']} /tmp/passbolt/passbolt*.deb"
  ignore_failure true
  live_stream true
  action :run
end
