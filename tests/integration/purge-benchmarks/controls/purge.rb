title 'Passbolt package purge healthcheck'

control 'passbolt-purge-01' do
  impact 0.9
  title 'Check that passbolt package is NOT installed'
  desc 'Check that passbolt package has been correctly purged'

  describe package("passbolt-#{input('passbolt_flavour')}-server") do
    it { should_not be_installed }
  end
end

control 'passbolt-purge-02' do
  impact 0.1
  title 'conf files erased'
  desc 'Configuration files are deleted on purge'
  describe directory('/etc/passbolt') do
    it { should_not exist }
  end
end

control 'passbolt-purge-03' do
  impact 0.2
  title 'php files are erased'
  desc 'Passbolt files are removed'
  describe directory('/usr/share/php/passbolt') do
    it { should_not exist }
  end
end
