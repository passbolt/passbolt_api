title 'Passbolt system files benchmark'

logs_dir = '/var/log/passbolt'
tmp_dir = '/var/lib/passbolt/tmp'
config_dir = '/etc/passbolt'
examples_dir = '/usr/share/passbolt/examples'
docs_dir = "/usr/share/doc/passbolt-#{input('passbolt_flavour')}-server"
crontabs_dir = '/etc/cron.d'
logrotate_dir = '/etc/logrotate.d'
cron_script = '/usr/share/php/passbolt/bin/cron'

webserver_owner = 'www-data'
webserver_group = 'www-data'

if os.family == 'redhat'
  webserver_owner = 'nginx'
  webserver_group = 'nginx'
end

control 'passbolt-logs-01' do
  impact 1
  title 'passbolt logs directory'
  desc 'passbolt logs directory is present and has write permissions for www user'
  describe directory("#{logs_dir}") do
    it { should exist }
    its('owner') { should eq webserver_owner }
    its('group') { should eq webserver_group }
    its('mode') { should cmp '0755' }
  end
end

control 'passbolt-tmp-01' do
  impact 1
  title 'passbolt temporary directory'
  desc 'passbolt temporary directory is present and has write permissions for www user'
  describe directory("#{tmp_dir}") do
    it { should exist }
    its('owner') { should eq webserver_owner }
    its('group') { should eq webserver_group }
    its('mode') { should cmp '0755' }
  end
end

control 'passbolt-config-01' do
  impact 1
  title 'passbolt config directory'
  desc 'passbolt config directory is present and has write permissions for www user'
  describe directory("#{config_dir}") do
    it { should exist }
    its('owner') { should eq 'root' }
    its('group') { should eq webserver_group }
    its('mode') { should cmp '00770' }
  end
end

control 'passbolt-examples-01' do
  impact 1
  title 'passbolt configuration examples directory'
  desc 'passbolt config examples directory is present'
  describe directory("#{examples_dir}") do
    it { should exist }
    its('owner') { should eq 'root' }
    its('group') { should eq 'root' }
    its('mode') { should cmp '00755' }
  end
end

control 'passbolt-docs-01' do
  impact 1
  title 'passbolt docs directory'
  desc 'passbolt docs directory is present'
  describe directory("#{docs_dir}") do
    it { should exist }
    its('owner') { should eq 'root' }
    its('group') { should eq 'root' }
    its('mode') { should cmp '00755' }
  end
end

control 'passbolt-crontab' do
  impact 0.5
  title 'passbolt crontab job'
  desc 'the passbolt crontab job must be installed'
  describe file("#{crontabs_dir}/passbolt-#{input('passbolt_flavour')}-server") do
    it { should exist }
    its('owner') { should eq 'root' }
    its('group') { should eq 'root' }
    its('mode') { should cmp '00644' }
  end
end

control 'passbolt-crontab-contents' do
  impact 0.5
  title 'passbolt crontab contents'
  desc 'the passbolt crontab contents should point to the cron script'
  describe file("#{crontabs_dir}/passbolt-#{input('passbolt_flavour')}-server") do
    its('content') { should match(%r{.*\$PASSBOLT_BASE_DIR/bin/cron.*}) }
    its('content') { should match(%r{.*PASSBOLT_BASE_DIR=/usr/share/php/passbolt.*}) }
  end
end

control 'passbolt-cron-script' do
  impact 0.5
  title 'passbolt cron_script'
  desc 'the passbolt cron file must be installed'
  describe file("#{cron_script}") do
    it { should exist }
    its('owner') { should eq 'root' }
    its('group') { should eq 'root' }
    its('mode') { should cmp '0755' }
  end
end

control 'passbolt-logrotate' do
  impact 0.5
  title 'passbolt logrotate'
  desc 'the passbolt crontab job must be installed'
  describe file("#{logrotate_dir}/passbolt-#{input('passbolt_flavour')}-server") do
    it { should exist }
    its('owner') { should eq 'root' }
    its('group') { should eq 'root' }
    its('mode') { should cmp '00644' }
  end
end
