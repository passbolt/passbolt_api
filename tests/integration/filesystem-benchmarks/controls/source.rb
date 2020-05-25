title 'Passbolt source files benchmark'

source_dir = '/usr/share/php/passbolt/src'
bin_dir = '/usr/share/php/passbolt/bin'

control 'passbolt-src-01' do                        # A unique ID for this control
  impact 1                                # The criticality, if this control fails.
  title 'source directory'             # A human-readable title
  desc 'source directory is present and have the correct permissions'
  describe directory("#{source_dir}") do                  # The actual test
    it { should exist }
    its('owner') { should eq 'root' }
    its('group') { should eq 'root' }
    its('mode') { should cmp '00755' }
  end
end

control 'passbolt-src-02' do                        # A unique ID for this control
  impact 1                                # The criticality, if this control fails.
  title 'source files'             # A human-readable title
  desc 'source files are present and have the correct permissions'
  command("find #{source_dir} -type f").stdout.split.each do |item|
    describe file(item) do
      its('owner') { should eq 'root' }
      its('group') { should eq 'root' }
      its('mode') { should cmp '00644' }
    end
  end
end

control 'passbolt-src-03' do                        # A unique ID for this control
  impact 1                                # The criticality, if this control fails.
  title 'source directories'             # A human-readable title
  desc 'source directories are present and have the correct permissions'
  command("find #{source_dir} -type d").stdout.split.each do |item|
    describe file(item) do
      its('owner') { should eq 'root' }
      its('group') { should eq 'root' }
      its('mode') { should cmp '00755' }
    end
  end
end

control 'passbolt-src-04' do                        # A unique ID for this control
  impact 1                                # The criticality, if this control fails.
  title 'bin test and ci-test files'             # A human-readable title
  desc 'Executable files for testing'
  files = [ 'test', 'ci-test' ]
  files.each do |item|
    describe file("#{bin_dir}/#{item}") do
      it  { should_not exist }
    end
  end
end

control 'passbolt-src-05' do                        # A unique ID for this control
  impact 1                                # The criticality, if this control fails.
  title 'cake bin scripts'             # A human-readable title
  desc 'Cake Executable files'
  files = [ 'cake', 'cake.php' ]
  files.each do |item|
    describe file("#{bin_dir}/#{item}") do
      its('owner') { should eq 'root' }
      its('group') { should eq 'root' }
      its('mode') { should cmp '00755' }
    end
  end
end
