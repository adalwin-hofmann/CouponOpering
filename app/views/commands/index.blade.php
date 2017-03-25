<!DOCTYPE html>
<html>
    <head>
        <link href="/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <h1>Commands</h1>
        <hr/>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Run</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Cache</td>
                    <td>Clear the Cache Table</td>
                    <td><a href="{{ URL::abs('/commands/cache/clear') }}">Clear</a></td>
                </tr>
                <tr>
                    <td>Images</td>
                    <td>Replace HTTPS with HTTP in image urls.</td>
                    <td><a href="{{ URL::abs('/commands/images/assets') }}">Assets</a></td>
                </tr>
                <tr>
                    <td>Images</td>
                    <td>Replace HTTPS with HTTP in image urls.</td>
                    <td><a href="{{ URL::abs('/commands/images/city_images') }}">City Images</a></td>
                </tr>
                <tr>
                    <td>Images</td>
                    <td>Replace HTTPS with HTTP in image urls.</td>
                    <td><a href="{{ URL::abs('/commands/images/contests') }}">Contest Images</a></td>
                </tr>
                <tr>
                    <td>Images</td>
                    <td>Replace HTTPS with HTTP in image urls.</td>
                    <td><a href="{{ URL::abs('/commands/images/entities') }}">Entities</a></td>
                </tr>
                <tr>
                    <td>Images</td>
                    <td>Replace HTTPS with HTTP in image urls.</td>
                    <td><a href="{{ URL::abs('/commands/images/offers') }}">Offers</a></td>
                </tr>
                <tr>
                    <td>Inventory Import</td>
                    <td>Get the DT Inventory File.</td>
                    <td><a href="{{ URL::abs('/commands/inventory_import/get_dt_file') }}">Get DT File</a></td>
                </tr>
                <tr>
                    <td>Inventory Import</td>
                    <td>Import DT Dealers.</td>
                    <td><a href="{{ URL::abs('/commands/inventory_import/dt_dealers') }}">Import DT Dealers</a></td>
                </tr>
                <tr>
                    <td>Inventory Import</td>
                    <td>Import DT Inventory.</td>
                    <td><a href="{{ URL::abs('/commands/inventory_import/dt_inventory') }}">Import DT Inventory</a></td>
                </tr>
                <tr>
                    <td>Inventory Import</td>
                    <td>Count Lines in Inventory File.</td>
                    <td><a href="{{ URL::abs('/commands/inventory_import/count') }}">Count DT Inventory</a></td>
                </tr>
                <tr>
                    <td>Inventory Import</td>
                    <td>Reset Vehicle Entities Indexes.</td>
                    <td><a href="{{ URL::abs('/commands/inventory_import/indexes') }}">DT Inventory Index Reset</a></td>
                </tr>
                <tr>
                    <td>Member Newsletter</td>
                    <td>Send Member Newsletter.</td>
                    <td><a href="{{ URL::abs('/scheduled/send_newsletter?newsletter=member') }}">Send Newsletter</a></td>
                </tr>
                <tr>
                    <td>Rank</td>
                    <td>Rank User Preferences.</td>
                    <td><a href="{{ URL::abs('/commands/rank/user') }}">Rank 50 Users</a></td>
                </tr>
                <tr>
                    <td>Rank</td>
                    <td>Rank Entity Popularity.</td>
                    <td><a href="{{ URL::abs('/commands/rank/entity') }}">Rank Entities</a></td>
                </tr>
                <tr>
                    <td>Search</td>
                    <td>Index Merchants.</td>
                    <td><a href="{{ URL::abs('/commands/search/merchant') }}">Index Merchants</a></td>
                </tr>
                <tr>
                    <td>Search</td>
                    <td>Index entities.</td>
                    <td><a href="{{ URL::abs('/commands/search/entity') }}">Index Entities</a></td>
                </tr>
                <tr>
                    <td>Search</td>
                    <td>Update Offer Counts for Locations.</td>
                    <td><a href="{{ URL::abs('/commands/search/has_coupons') }}">Update Offer Counts</a></td>
                </tr>
                <tr>
                    <td>Search</td>
                    <td>Index locations with offers.</td>
                    <td><a href="{{ URL::abs('/commands/search/coupons_index') }}">With Offers</a></td>
                </tr>
                <tr>
                    <td>Search</td>
                    <td>Index locations without offers.</td>
                    <td><a href="{{ URL::abs('/commands/search/no_coupons_index') }}">Without Offers</a></td>
                </tr>
                <tr>
                    <td>Sitemap</td>
                    <td>Build Sitemaps.</td>
                    <td><a href="{{ URL::abs('/commands/sitemap') }}">Build</a></td>
                </tr>
                <tr>
                    <td>SOCT</td>
                    <td>Import SOCT Dealers and Inventory.</td>
                    <td><a href="{{ URL::abs('/commands/soct/import_live_inventory') }}">Import SOCT</a></td>
                </tr>
                <tr>
                    <td>Warmup</td>
                    <td>Warm up recommendations cache.</td>
                    <td><a href="{{ URL::abs('/scheduled/warmup') }}">Warmup</a></td>
                </tr>
                <tr>
                    <td>Yipit</td>
                    <td>Update Yipit Deals.</td>
                    <td><a href="{{ URL::abs('/scheduled/yipit') }}">Update Yipit</a></td>
                </tr>
            </tbody>
        </table>
    </body>
</html>