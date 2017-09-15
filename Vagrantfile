# -*- mode: ruby -*-
# vi: set ft=ruby :
require 'yaml'
require 'fileutils'

config = {
  local: './vagrant/config/vagrant-local.yml',
  example: './vagrant/config/vagrant-local.example.yml',
  pwd: './vagrant/config/pwd.yml'
}

options = YAML.load_file config[:local]
pwd = YAML.load_file config[:pwd]
# check github token
if options['github_token'].nil? || options['github_token'].to_s.length != 40
  puts "You must place REAL GitHub token into configuration:\n/yii2-app-advancded/vagrant/config/vagrant-local.yml"
  exit
end
required_plugins = %w( vagrant-triggers )
required_plugins.each do |plugin|
  system "vagrant plugin install #{plugin}" unless Vagrant.has_plugin? plugin
  exec "vagrant #{ARGV.join' '}" unless Vagrant.has_plugin? plugin
end
Vagrant.configure("2") do |config|
  config.vm.box = 'ubuntu/trusty64'
  config.vm.network "private_network", ip: options['ip']
  config.vm.network "forwarded_port", guest: 80, host: 8080
  config.vm.network "forwarded_port", guest: 22, host: 2222, auto_correct: false, id: "ssh"
  config.vm.box_check_update = options['box_check_update']
  config.vm.provider 'virtualbox' do |vb|
    vb.cpus = options['cpus']
    vb.memory = options['memory']
    vb.name = options['machine_name']
    vb.gui = false

  end

  config.vm.define options['machine_name']
  config.vm.hostname = options['machine_name']
  #config.vm.synced_folder './', '/app', owner: 'www-data', group: 'www-data'
  config.vm.synced_folder './vagrant', '/vagrant'
  config.vm.synced_folder '.', '/vagrant', disabled: true
  #ssh config
  config.ssh.username = "vagrant"
  config.ssh.password = "vagrant"
  config.ssh.guest_port = 2222
  config.ssh.forward_agent = true
  config.ssh.insert_key = false
  config.ssh.private_key_path = false

  config.vm.provision 'shell', path: './vagrant/provision/once-as-root.sh', args: [options['timezone'],pwd['root_db_password'],pwd['g4a_db_password']]
  config.vm.provision 'shell', path: './vagrant/provision/once-as-vagrant.sh', args: [options['github_token'],pwd['root_db_password']], privileged: false
  config.vm.provision 'shell', path: './vagrant/provision/always-as-root.sh', run: 'always', args: [pwd['root_db_password']]
end