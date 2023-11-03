# Laravel ---------------------------------------------

built-laravel:
	@$(MAKE) -C ShabuNow build


run-laravel:
	@$(MAKE) -C ShabuNow run

stop-laravel:
	@$(MAKE) -C ShabuNow stop

fresh-laravel:
	@$(MAKE) -C ShabuNow fresh

restart-laravel: stop-laravel run-laravel
shutdown-laravel: stop-laravel
down-laravel: stop-laravel
start-laravel: run-laravel

add-shortcut-laravel:
	@$(MAKE) -C ShabuNow shortcut

# Laravel END -----------------------------------------

built-nuxt:
	@npm install
	@npm audit fix
	@npm run dev

install-nodejs:
	curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.5/install.sh | bash
	npm install
	npm audit fix
	npm run dev
	nvm install node
