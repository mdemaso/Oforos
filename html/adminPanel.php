<?
	// Contains the content for the Admin Bar
	
	// Function to build the Admin Bar
	function adminPanelDotPHP(){
		if($_SESSION['admin'] == 1){
			if(isset($_GET['message'])){ ?>
				<a href="<? printHTML(fullURL(getLangVar("messageURL"), "edit") . $GLOBALS['adminObjectId']); ?>">Edit Message</a> | 
				<a href="<? printHTML(fullURL(getLangVar("messageURL"), "delete") . $GLOBALS['adminObjectId']); ?>">Delete Message</a> | 
				<a href="<? printHTML(fullURL("environment/", "edit")); ?>">Environment Variables</a>
	<?		}else if(isset($_GET['event'])){ ?>
				<a href="<? printHTML(fullURL(getLangVar("eventURL"), "edit") . $GLOBALS['adminObjectId']); ?>">Edit Event</a> | 
				<a href="<? printHTML(fullURL(getLangVar("eventURL"), "delete") . $GLOBALS['adminObjectId']); ?>">Delete Event</a> | 
				<a href="<? printHTML(fullURL("environment/", "edit")); ?>">Environment Variables</a>
	<?		}else if(isset($_GET['file'])){ ?>
				<a href="<? printHTML(fullURL(getLangVar("fileURL"), "edit") . $GLOBALS['adminObjectId']); ?>">Edit File</a> | 
				<a href="<? printHTML(fullURL(getLangVar("fileURL"), "delete") . $GLOBALS['adminObjectId']); ?>">Delete File</a> | 
				<a href="<? printHTML(fullURL("environment/", "edit")); ?>">Environment Variables</a>
	<?		}else if(isset($_GET['user'])){ ?>
				<a href="<? printHTML(fullURL(getLangVar("userURL"), "edit") . $GLOBALS['adminObjectId']); ?>">Edit User</a> | 
				<a href="<? printHTML(fullURL(getLangVar("userURL"), "delete") . $GLOBALS['adminObjectId']); ?>">Delete User</a> | 
				<a href="<? printHTML(fullURL("environment/", "edit")); ?>">Environment Variables</a>
	<?		}else{	?>
				<a href="<? printHTML(fullURL("environment/", "edit")); ?>">Environment Variables</a>
		<?	}
		}
	}
?>