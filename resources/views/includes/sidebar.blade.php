<!-- Sidebar -->
<div class="sidebar" id="sidebar">
	<div class="sidebar-inner slimscroll">
		<div id="sidebar-menu" class="sidebar-menu">
			
			<ul>
				<li class="menu-title"> 
					<span>Main</span>
				</li>
				<li class="{{ Request::routeIs('dashboard') ? 'active' : '' }}"> 
					<a href="{{route('dashboard')}}"><i class="fe fe-home"></i> <span>Dashboard</span></a>
				</li>
				
				@can('view-category')
				<li class="{{ Request::routeIs('categories') ? 'active' : '' }}"> 
					<a href="{{route('categories')}}"><i class="fe fe-layout"></i> <span>Categories</span></a>
				</li>
				@endcan
				
				@can('view-products')
				<li class="submenu">
					<a href="#"><i class="fe fe-document"></i> <span> Products</span> <span class="menu-arrow"></span></a>
					<ul style="display: none;">
						@can('view-products')<li><a class="{{ Request::routeIs(('products')) ? 'active' : '' }}" href="{{route('products')}}">Products</a></li>@endcan
						@can('create-product')<li><a class="{{ Request::routeIs('add-product') ? 'active' : '' }}" href="{{route('add-product')}}">Add Product</a></li>@endcan
						@can('view-outstock-products')<li><a class="{{ Request::routeIs('outstock') ? 'active' : '' }}" href="{{route('outstock')}}">Out-Stock</a></li>@endcan
						@can('view-expired-products')<li><a class="{{ Request::routeIs('expired') ? 'active' : '' }}" href="{{route('expired')}}">Expired</a></li>@endcan
					</ul>
				</li>
				@endcan
				@can('view-items')
				<li class="submenu">
					<a href="#"><i class="fe fe-document"></i> <span> Items</span> <span class="menu-arrow"></span></a>
					<ul style="display: none;">
						@can('view-items')<li><a class="{{ Request::routeIs(('items')) ? 'active' : '' }}" href="{{route('items')}}">Item Lists</a></li>@endcan
						@can('create-item')<li><a class="{{ Request::routeIs('add-item') ? 'active' : '' }}" href="{{route('add-item')}}">Add Item</a></li>@endcan
						@can('view-outstock-items')<li><a class="{{ Request::routeIs('outstock') ? 'active' : '' }}" href="{{route('outstock')}}">Out-Stock Items</a></li>@endcan
						@can('view-expired-items')<li><a class="{{ Request::routeIs('expired') ? 'active' : '' }}" href="{{route('expired')}}">Expired Items</a></li>@endcan
						@can('view-item-price')<li><a class="{{ Request::routeIs('item-price') ? 'active' : '' }}" href="{{route('item-price')}}">Item Prices</a></li>@endcan
					</ul>
				</li>
				@endcan
				@can('inventory-settings')
				<li class="submenu">
					<a href="#"><i class="fe fe-gear"></i> <span> Inventory Settings</span> <span class="menu-arrow"></span></a>
					<ul style="display: none;">
						@can('manage-inventory-store')<li><a class="{{ Request::routeIs('stores') ? 'active' : '' }}" href="{{route('stores')}}">Stores</a></li>@endcan
						@can('manage-item-units')<li class="{{ Request::routeIs('itemUnits') ? 'active' : '' }}"><a href="{{route('itemUnits')}}"> <span>Item Units</span></a></li>@endcan
						@can('manage-item-groups')<li><a class="{{ Request::routeIs('itemGroups') ? 'active' : '' }}" href="{{route('itemGroups')}}">Item Groups</a></li>@endcan 
						@can('manage-item-categories')<li><a class="{{ Request::routeIs('itemCategory') ? 'active' : '' }}" href="{{route('itemCategory')}}">Item Categories</a></li>@endcan
						@can('manage-item-price-type')<li><a class="{{ Request::routeIs('priceType') ? 'active' : '' }}" href="{{route('priceType')}}">Price Types</a></li>@endcan
						@can('manage-supplier')<li><a class="{{ Request::routeIs('supplier') ? 'active' : '' }}" href="{{route('supplier')}}">Suppliers</a></li>@endcan
						@can('manage-inventory-store')<li><a class="{{ Request::routeIs('adjFactors') ? 'active' : '' }}" href="{{route('adjFactors')}}">Adjustments Factors</a></li>@endcan
					</ul>
				</li>
				@endcan	
				@can('stock-movements')
				<li class="submenu">
					<a href="#"><i class="fe fe-gear"></i> <span> Stock Movements</span> <span class="menu-arrow"></span></a>
					<ul style="display: none;">
						@can('manage-invoices')<li><a class="{{ Request::routeIs('invoices') ? 'active' : '' }}" href="{{route('invoices')}}">Invoice</a></li>@endcan
						@can('manage-stock-requisition')<li><a class="{{ Request::routeIs('requisition') ? 'active' : '' }}" href="{{route('requisition')}}">Requisition</a></li>@endcan
						@can('manage-stock-issuing')<li class="{{ Request::routeIs('issuing') ? 'active' : '' }}"><a href="{{route('issuing')}}"> <span>Issuing</span></a></li>@endcan
						@can('view-stocks-binCard')<li><a class="{{ Request::routeIs('binCards') ? 'active' : '' }}" href="{{route('binCards')}}">Bin Card</a></li>@endcan 
						@can('view-stocks-ledger')<li><a class="{{ Request::routeIs('ledger') ? 'active' : '' }}" href="{{route('ledger')}}">Ledger</a></li>@endcan
						@can('manage-item-price-type')<li><a class="{{ Request::routeIs('detailedStockStatus') ? 'active' : '' }}" href="{{route('detailedStockStatus')}}">Detailed Stock Status</a></li>@endcan
						@can('manage-supplier')<li><a class="{{ Request::routeIs('stockStatus') ? 'active' : '' }}" href="{{route('stockStatus')}}">Stock Status</a></li>@endcan
						@can('manage-inventory-store')<li><a class="{{ Request::routeIs('adjustments') ? 'active' : '' }}" href="{{route('adjustments')}}">Adjustments</a></li>@endcan
					</ul>
				</li>
				@endcan	
				@can('view-purchase')
				<li class="submenu">
					<a href="#"><i class="fe fe-star-o"></i> <span> Purchase</span> <span class="menu-arrow"></span></a>
					<ul style="display: none;">
						<li><a class="{{ Request::routeIs('purchases') ? 'active' : '' }}" href="{{route('purchases')}}">Purchase</a></li>
						@can('create-purchase')
						<li><a class="{{ Request::routeIs('add-purchase') ? 'active' : '' }}" href="{{route('add-purchase')}}">Add Purchase</a></li>
						@endcan
					</ul>
				</li>
				@endcan
				@can('view-pos')
				<li class="submenu">
					<a href="#"><i class="fe fe-star-o"></i> <span> Point Of Sales</span> <span class="menu-arrow"></span></a>
					<ul style="display: none;">
						<li><a class="{{ Request::routeIs('pos-normal') ? 'active' : '' }}" href="{{route('pos-normal')}}">Open POS</a></li>
						@can('create-purchase')
						<li><a class="{{ Request::routeIs('pos-quotations') ? 'active' : '' }}" href="{{route('pos-quotations')}}">Quotations</a></li>
						@endcan
						@can('create-purchase')
						<li><a class="{{ Request::routeIs('pos-orders') ? 'active' : '' }}" href="{{route('pos-orders')}}">Orders</a></li>
						@endcan
						@can('pos-creator-reports')
						<li><a class="{{ Request::routeIs('today-collections') ? 'active' : '' }}" href="{{route('today-collections')}}">Today Collections</a></li>
						@endcan
					</ul>
				</li>
				@endcan
				@can('view-sales')
				<li><a class="{{ Request::routeIs('sales') ? 'active' : '' }}" href="{{route('sales')}}"><i class="fe fe-activity"></i> <span>Sales</span></a></li>
				@endcan
				@can('view-supplier')
				<li class="submenu">
					<a href="#"><i class="fe fe-user"></i> <span> Supplier</span> <span class="menu-arrow"></span></a>
					<ul style="display: none;">
						<li><a class="{{ Request::routeIs('suppliers') ? 'active' : '' }}" href="{{route('suppliers')}}">Supplier</a></li>
						@can('create-supplier')<li><a class="{{ Request::routeIs('add-supplier') ? 'active' : '' }}" href="{{route('add-supplier')}}">Add Supplier</a></li>@endcan
					</ul>
				</li>
				@endcan

				@can('view-reports')
				<li class="submenu">
					<a href="#"><i class="fe fe-document"></i> <span> Reports</span> <span class="menu-arrow"></span></a>
					<ul style="display: none;">
						<li><a class="{{ Request::routeIs('reports') ? 'active' : '' }}" href="{{route('reports')}}">Reports</a></li>
					</ul>
				</li>
				@endcan

				@can('view-access-control')
				<li class="submenu">
					<a href="#"><i class="fe fe-lock"></i> <span> Access Control</span> <span class="menu-arrow"></span></a>
					<ul style="display: none;">
						@can('view-permission')
						<li><a class="{{ Request::routeIs('permissions') ? 'active' : '' }}" href="{{route('permissions')}}">Permissions</a></li>
						@endcan
						@can('view-role')
						<li><a class="{{ Request::routeIs('roles') ? 'active' : '' }}" href="{{route('roles')}}">Roles</a></li>
						@endcan
					</ul>
				</li>					
				@endcan

				@can('view-users')
				<li class="{{ Request::routeIs('users') ? 'active' : '' }}"> 
					<a href="{{route('users')}}"><i class="fe fe-users"></i> <span>Users</span></a>
				</li>
				@endcan
				
				<li class="{{ Request::routeIs('profile') ? 'active' : '' }}"> 
					<a href="{{route('profile')}}"><i class="fe fe-user-plus"></i> <span>Profile</span></a>
				</li>
				@can('view-settings')
				<li class="{{ Request::routeIs('settings') ? 'active' : '' }}"> 
					<a href="{{route('settings')}}">
						<i class="fa fa-gears"></i>
						 <span> Settings</span>
					</a>
				</li>
				@endcan
			</ul>
		</div>
	</div>
</div>
<!-- /Sidebar -->