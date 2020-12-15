<?php
function activePageClass( $page )
{
	$pagename = basename($_SERVER['PHP_SELF']);
    if( is_array($page))
    {
        if( in_array( $pagename, $page ))
        {
            return 'active';
        }
    }
    else if( $pagename == $page )
    {
        return 'active';
    }

    return '';
}

?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<a class="navbar-brand" href="#">Invoice Generator</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
			<!-- <li class="nav-item active">
				<a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
			</li> -->

			<li class="nav-item">
				<a class="nav-link <?php echo activePageClass('add_company.php'); ?>" href="add_company.php" >Update Company Info</a>
			</li>
			<li class="nav-item dropdown">

				<a class="nav-link dropdown-toggle <?php echo activePageClass(['add-customer.php', 'list-customer.php', 'edit-customer.php' ]); ?>" id="navbarDropdownCustomer" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Customers</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownCustomer">
                    <a class="dropdown-item <?php echo activePageClass('add-customer.php'); ?>" href="add-customer.php">Add Customer</a>
                    <a class="dropdown-item <?php echo activePageClass('list-customer.php'); ?>" href="list-customer.php">List Customer</a>

                </div>

			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle <?php echo activePageClass(['add-invoice.php','list-invoices.php', 'edit-invoice.php']); ?>" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Invoice
				</a>

				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					<a class="dropdown-item <?php echo activePageClass('add-invoice.php'); ?>" href="add-invoice.php">Add Invoice</a>
					<a class="dropdown-item <?php echo activePageClass('list-invoices.php'); ?>" href="list-invoices.php">List Invoices</a>

				</div>
			</li>
			<!--<li class="nav-item">
				<a class="nav-link disabled" href="#">Disabled</a>
			</li> -->
		</ul>

		<form action='' method="POST"class="form-inline my-2 my-lg-0">
            <?php
                if( isset($_SESSION['id']) && '' != trim($_SESSION['id']) )
                {
                    echo "Welcome! ".$_SESSION['user'];
                }
            ?>
			<!--<input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
			<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button> -->

<a class="btn btn-danger my-2 my-sm-0 ml-3" href="logout.php">Logout</a>



		</form>
	</div>
</nav>
