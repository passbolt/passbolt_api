title 'Passbolt webroot files benchmark'

source_dir = '/usr/share/php/passbolt/webroot'

control 'passbolt-webroot-01' do                        # A unique ID for this control
  impact 1                                # The criticality, if this control fails.
  title 'webroot directory'             # A human-readable title
  desc 'webroot directory is present and have the correct permissions'
  describe directory("#{source_dir}") do                  # The actual test
    it { should exist }
    its('owner') { should eq 'root' }
    its('group') { should eq 'root' }
    its('mode') { should cmp '00755' }
  end
end

control 'passbolt-webroot-02' do                        # A unique ID for this control
  impact 1                                # The criticality, if this control fails.
  title 'webroot dirs'             # A human-readable title
  desc 'webroot files are present and have the correct permissions'
  command("find #{source_dir} -type d").stdout.split.each do |item|
    describe file(item) do
      owner = 'root'
      group = 'root'
    if item.include? "public"
      owner = 'www-data'
      group = 'www-data'
    end
      its('owner') { should eq owner }
      its('group') { should eq group }
      its('mode') { should cmp '00755' }
    end
  end
end

control 'passbolt-webroot-03' do                        # A unique ID for this control
  impact 1                                # The criticality, if this control fails.
  title 'webroot files'             # A human-readable title
  desc 'webroot files are present and have the correct permissions'
  command("find #{source_dir} -type f").stdout.split.each do |item|
    describe file(item) do
      owner = 'root'
      group = 'root'
    if item.include? "public"
      owner = 'www-data'
      group = 'www-data'
    end
      its('owner') { should eq owner }
      its('group') { should eq group }
      its('mode') { should cmp '00644' }
    end
  end
end
