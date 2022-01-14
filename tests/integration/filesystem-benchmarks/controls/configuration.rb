# encoding: utf-8
# copyright: 2018, The Authors

title 'Passbolt filesystem configuration benchmarks'

config_dir = '/etc/passbolt'
gnupg_dir = '/var/lib/passbolt/.gnupg'

webserver_owner = 'www-data'
webserver_group = 'www-data'

if os.family == 'redhat'
  webserver_owner = 'nginx'
  webserver_group = 'nginx'
end

# you add controls here
control 'passbolt-config-01' do                        # A unique ID for this control
  impact 1                                # The criticality, if this control fails.
  title 'app.php is installed'             # A human-readable title
  desc 'app.php config file is installed and with correct permissions'
  describe file("#{config_dir}/app.php") do                  # The actual test
    it { should exist }
    its('owner') { should eq 'root' }
    its('group') { should eq webserver_group }
    its('mode') { should cmp '00640' }
  end
end

# you add controls here
control 'passbolt-config-02' do                        # A unique ID for this control
  impact 1                                # The criticality, if this control fails.
  title 'bootstrap.php file is installed'             # A human-readable title
  desc 'bootstrap.php file is present with correct permissions'
  describe file("#{config_dir}/bootstrap.php") do                  # The actual test
    it { should exist }
    its('owner') { should eq 'root' }
    its('group') { should eq webserver_group }
    its('mode') { should cmp '00640' }
  end
end

# you add controls here
control 'passbolt-config-03' do                        # A unique ID for this control
  impact 1                                # The criticality, if this control fails.
  title 'bootstrap_cli.php file is installed'             # A human-readable title
  desc 'bootstrap_cli.php file is present with correct permissions'
  describe file("#{config_dir}/bootstrap_cli.php") do                  # The actual test
    it { should exist }
    its('owner') { should eq 'root' }
    its('group') { should eq webserver_group }
    its('mode') { should cmp '00640' }
  end
end

# you add controls here
control 'passbolt-config-04' do                        # A unique ID for this control
  impact 1                                # The criticality, if this control fails.
  title 'bootstrap_plugins.php file is installed'             # A human-readable title
  desc 'bootstrap_plugins.php file is present with correct permissions'
  describe file("#{config_dir}/bootstrap_plugins.php") do                  # The actual test
    it { should exist }
    its('owner') { should eq 'root' }
    its('group') { should eq webserver_group }
    its('mode') { should cmp '00640' }
  end
end

# you add controls here
control 'passbolt-config-06' do                        # A unique ID for this control
  impact 1                                # The criticality, if this control fails.
  title 'requirements.php file is installed'             # A human-readable title
  desc 'requirements.php file is present with correct permissions'
  describe file("#{config_dir}/requirements.php") do                  # The actual test
    it { should exist }
    its('owner') { should eq 'root' }
    its('group') { should eq webserver_group }
    its('mode') { should cmp '00640' }
  end
end

# you add controls here
control 'passbolt-config-07' do                        # A unique ID for this control
  impact 1                                # The criticality, if this control fails.
  title 'routes.php file is installed'             # A human-readable title
  desc 'routes.php file is present with correct permissions'
  describe file("#{config_dir}/routes.php") do                  # The actual test
    it { should exist }
    its('owner') { should eq 'root' }
    its('group') { should eq webserver_group }
    its('mode') { should cmp '00640' }
  end
end

# you add controls here
control 'passbolt-config-08' do                        # A unique ID for this control
  impact 1                                # The criticality, if this control fails.
  title 'version.php file is installed'             # A human-readable title
  desc 'version.php file is present with correct permissions'
  describe file("#{config_dir}/version.php") do                  # The actual test
    it { should exist }
    its('owner') { should eq 'root' }
    its('group') { should eq webserver_group }
    its('mode') { should cmp '00640' }
  end
end

# you add controls here
control 'passbolt-config-09-1' do                        # A unique ID for this control
  impact 1                                # The criticality, if this control fails.
  title 'gpg directory is installed'             # A human-readable title
  desc 'gpg directory is present with correct permissions'
  describe directory("#{config_dir}/gpg") do                  # The actual test
    it { should exist }
    its('owner') { should eq 'root' }
    its('group') { should eq webserver_group }
    its('mode') { should cmp '00770' }
    it 'should be empty' do
      expect(command("ls #{config_dir}/gpg | wc -l").stdout).to eq "0\n"
    end
  end
end

# you add controls here
control 'passbolt-config-09-2' do                        # A unique ID for this control
  impact 1                                # The criticality, if this control fails.
  title 'gnugpg directory is installed'             # A human-readable title
  desc 'gnugpg directory is present with correct permissions for web server user'
  describe directory("#{gnupg_dir}") do                  # The actual test
    it { should exist }
    its('owner') { should eq webserver_owner }
    its('group') { should eq webserver_group }
    its('mode') { should cmp '00700' }
    it 'should be empty' do
      expect(command("ls #{gnupg_dir} | wc -l").stdout).to eq "0\n"
    end
  end
end

control 'passbolt-config-10-1' do                        # A unique ID for this control
  impact 1                                # The criticality, if this control fails.
  title 'jwt directory is installed'             # A human-readable title
  desc 'jwt directory is present with correct permissions'
  describe directory("#{config_dir}/jwt") do                  # The actual test
    it { should exist }
    its('owner') { should eq 'root' }
    its('group') { should eq webserver_group }
    its('mode') { should cmp '00770' }
    it 'should be empty' do
      expect(command("ls #{config_dir}/gpg | wc -l").stdout).to eq "0\n"
    end
  end
end

control 'passbolt-config-10-2' do                        # A unique ID for this control
  impact 1                                # The criticality, if this control fails.
  title 'jwt key file is installed'             # A human-readable title
  desc 'jwt key file is present with correct permissions'
  describe file("#{config_dir}/jwt/jwt.key") do                  # The actual test
    it { should exist }
    its('owner') { should eq 'root' }
    its('group') { should eq webserver_group }
    its('mode') { should cmp '00640' }
    it 'should be empty' do
      expect(command("ls #{config_dir}/gpg | wc -l").stdout).to eq "0\n"
    end
  end
end

control 'passbolt-config-10-3' do                        # A unique ID for this control
  impact 1                                # The criticality, if this control fails.
  title 'jwt pem file is installed'             # A human-readable title
  desc 'jwt pem file is present with correct permissions'
  describe file("#{config_dir}/jwt/jwt.pem") do                  # The actual test
    it { should exist }
    its('owner') { should eq 'root' }
    its('group') { should eq webserver_group }
    its('mode') { should cmp '00640' }
    it 'should be empty' do
      expect(command("ls #{config_dir}/gpg | wc -l").stdout).to eq "0\n"
    end
  end
end

