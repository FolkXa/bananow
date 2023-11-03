built:
	@npm install
	@npm run dev

run:
	@npm run dev

install-nodejs:
	curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.5/install.sh | bash
	npm install
	npm audit fix
	npm run dev
	nvm install node
