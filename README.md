

# Code challenge for Catch

## Contact Details
- **Name:** Matt Hartshorn
- **Mobile:** 0437 172 122
- **Email:** dev@matthartshorn.com 


## Running the application
- install [Docker desktop](https://www.docker.com/products/docker-desktop/) and run the Docker desktop app
- run `./vendor/bin/sail up`
- run `./vendor/bin/sail artisan order:process`

## Additional information
- application entry point is `App/Console/Commands/ProcessOrder.php`
- csv output file is located at `storage/orders/out.csv`
