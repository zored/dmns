# Docker Machine DNS

## Usage
- Coming soon in composer.

- Update DNS rules on MacOS:
	```bash
	sudo dmns host:dns:update
	```

- Start DNS sever:
	```bash
	sudo dmns host:dns:update
	```
	> Server will start automatically on reboot.

- Have fun!
	```bash
	$ docker-machine ip example
	192.168.99.104

	$ ping example.d
	PING example.d (192.168.99.104)
	```

## Settings
You may use `--tld d` to change TLD for your urls. For example, `--tld test` will suit your `example.test` domains. 