# TODO: Fix Admin Transaction History Error

## Completed Tasks
- [x] Update PaymentController adminIndex method to filter payments with existing user and event relations using whereHas('user')->whereHas('event')
- [x] Change adminIndex to use paginate(50) instead of get() for better performance
- [x] Rename export method from exportAdmin to export to match the route definition
- [x] Update admin payments index view to handle pagination (correct numbering and add pagination links)
- [x] Disable DataTable client-side paging since using server-side pagination

## Followup Steps
- [x] Test the admin payments page to ensure no errors occur when clicking "Riwayat Transaksi"
- [x] Verify that the export functionality works correctly
- [x] Check that pagination works properly with large datasets
