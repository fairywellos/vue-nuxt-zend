const listingConfig = {
    data(){
        return {
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            listingType : [
                {id: 1, name: 'General'},
                {id: 2, name: 'Item'},
                {id: 3, name: 'Service'},
                {id: 4, name: 'Deal'},
            ],
            listingCondition : [
                {id: 1, name: 'Used'},
                {id: 2, name: 'New'},
            ],
            listingTradeType : [
                {id: 1, name: 'Trade offer'},
                {id: 2, name: 'Cash offer'},
                {id: 3, name: 'Trade and cash offer'},
            ],
            shippingStatus : [
                {id: 1, name: 'Shipping'},
                {id: 0, name: 'Not shipping'},
            ],
			localTradeStatus : [
				{id: 1, name: 'On'},
				{id: 0, name: 'Off'},
			],
            listingStatus : [
                {id: 1, name: 'Active'},
                {id: 2, name: 'Inactive'},
            ],
            legend : {
                main_category: 'Main category',
                listing_tags: 'Listing tags',
                listing_type: 'Listing type',
                title: 'Title',
                description: 'Description',
                price: 'Price',
                condition: 'Condition',
                trade_or_cash: 'Trade or cash',
                location: 'Location',
				year: 'Year',
				brand: 'Brand',
				color: 'Color',
				material: 'Material',
                qty: 'Qty',
                shipping: 'Shipping',
                listing_images: 'Listing images',
				local_trades_only: 'Local trades only',
				available_date: 'Available date',
				meta_tags: 'Meta tags',
				notes: 'Notes',
            },
        }
    },
}

export default listingConfig;
