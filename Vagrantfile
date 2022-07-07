Vagrant.configure("2") do |config|
    config.vm.box = "ubuntu/focal64"
    config.vm.host_name = 'app.das-programm.local-vm'
    config.vm.define "techni"

    config.vm.network "private_network", ip: "192.168.56.11"
    config.vm.hostname = "app.das-programm.local-vm"

    config.vm.synced_folder "", "/var/www/techni.b", :nfs => { :mount_options => ["dmode=777", "fmode=666"] }

    config.vm.provider "virtualbox" do |techni|
        techni.memory = 2048
        techni.gui = false
        # vb.cpus = 4
    end

    config.vm.provision "shell", path: "provision/provision.sh"
end
