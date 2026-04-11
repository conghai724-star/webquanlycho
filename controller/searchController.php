<?php
/**
 * Project: thuvien.
 * File: searchController.php.
 * Author: Ken Zaki
 * Email: kenzaki@xiao.vn
 * Create Date: 17:08 - 21/10/2013
 * Website: www.xiao.vn
 */
Class searchController extends baseController
{
    public function index()
    {

    }
	public function trans()
	{
		$text = '{
	"header": {
		"notifications": "Notifications",
		"languages": "Languages",
		"mails": "mails",
		"profile": "Profile",
		"inbox": "My Inbox",
		"logout": "Logout",
		"search": {
			"text": "Search here ..."
		},
		"heading": {
			"head1": "You have",
			"head2": "new mails",
			"head3": "See all mails",
			"head4": "Update Available"
		}
	},
	"theme_setting": {
		"header": {
			"head1": "Sidebar Color Options",
			"head2": "Color Palettes",
			"head3": "Colors Options",
			"head4": "Sidebar Background Colors",
			"head5": "Sidebar Background Images",
			"head6": "Background Image",
			"head7": "Background Colors",
			"head8": "Background Images",
			"head9": "Sidebar Background Colors",
			"head10": "Sidebar Font Colors",
			"head11": "Sidebar Layout Options",
			"head12": "Configurations",
			"head13": "Collapsed Menu",
			"head14": "Sidebar Width"
		},
		"width_options": [
			{ "key": "small", "value": "Small" },
			{ "key": "medium", "value": "Medium" },
			{ "key": "large", "value": "Large" }
		],
		"layout_options": [
			{ "key": "light", "value": "Pre-built Colors" },
			{ "key": "custom-colors", "value": "Custom Colors" }
		],
		"placeholders":{
			"placeholder1": "-- Select Layout --",
			"placeholder2": "-- Select Sidebar Width --"
		}
	},
	"footer": {
		"copyright": "Copyright",
		"text1": "All rights reserved"
	},
	"common": {
		"action": "Action",
		"add": "Add",
		"add_to_timesheet": "Add to Timesheet",
		"back": "Back",
		"created": "Created",
		"updated": "Updated",
		"last_updated": "Last Updated",
		"toggle_dropdown": "Toggle Dropdown",
		"submit": "Submit",
		"reset": "Reset",
		"apply": "Apply",
		"new": "new",
		"edit": "Edit",
		"delete": "Delete",
		"update": "Update",
		"view": "View",
		"view_more": "View More",
		"create": "Create",
		"save": "Save",
		"online": "Online",
		"offline": "Offline",
		"fullscreen": "Fullscreen",
		"total": "Total",
		"pending": "Pending",
		"issues": "Issues",
		"show": "Show",
		"search": "Search ...",
		"cancel": "Cancel",
		"close": "Close",
		"import": "Import",
		"assign": "Assign",
		"remove_file": "Remove File",
		"change": "Change",
		"invite": "Invite",
		"remove": "Remove",
		"download": "Download",
		"restore": "Restore",
		"hours": "hours",
		"move":"Move",
		"unassigned": "Unassigned",
		"unassign": "Unassign",
		"detail": "Detail",
		"profile": "Profile",
		"true": "True",
		"false": "False",
		"12_hours": "12 Hours",
		"24_hours": "24 Hours",
		"kanban": "Kanban",
		"switch_to_kanban": "Switch to Kanban",
		"switch_to_list": "Switch to List",
		"switch_to_calendar": "Switch to Calendar",
		"calendar": "Calendar",
		"upload": "Upload File",
		"lists": "List View",
		"list": "List",
		"details": "Details",
		"comment": "Comment",
		"create_comment": "Create Comment",
		"view_comments": "View Comments",
		"not_allowed": "Not allowed in demo.",
		"drag_file": "Drag a file or Browse",
		"tooltip": {
			"user_csv_sample": "Sample User CSV",
			"team_csv_sample": "Sample Team CSV",
			"project_csv_sample": "Sample Project CSV",
			"task_csv_sample": "Sample Task CSV"
		},
		"swal": {
			"title": "Are you sure?",
			"text": "You will not be able to recover this ",
			"text1": "You will not be able to recover this department!. Before delete, make sure that you have deleted all users related to this department.",
			"text2": "You will not be able to recover this user!. Before delete, make sure that you have remove user from teams, projects, tasks, defects and incidents etc..",
			"text3": "You will not be able to recover this client!. Before delete, make sure that you have remove client from teams, projects, tasks, defects and incidents etc..",
			"text4": "You will not be able to recover this project!. Before delete, make sure that you have deleted all tasks, defects and incidents related to this project.",
			"confirmButtonText": "Yes, delete it!",
			"cancelButtonText": "No, keep it"
		},
		"datatable": {
			"sEmptyTable":     "No data available in table",
			"sInfo":           "Showing _START_ to _END_ of _TOTAL_ entries",
			"sInfoEmpty":      "Showing 0 to 0 of 0 entries",
			"sInfoFiltered":   "(filtered from _MAX_ total entries)",
			"sInfoPostFix":    "",
			"sInfoThousands":  ",",
			"sLengthMenu":     "Show _MENU_",
			"sLoadingRecords": "Loading...",
			"sProcessing":     "Processing...",
			"sZeroRecords":    "No matching records found",
			"sSearchPlaceholder": "Search ...",
			"oPaginate": {
				"sFirst":    "First",
				"sLast":     "Last",
				"sNext":     "Next",
				"sPrevious": "Previous"
			},
			"oAria": {
				"sSortAscending":  ": activate to sort column ascending",
				"sSortDescending": ": activate to sort column descending"
			}
		},
		"empty_message": {
			"mails": "There are no any mails.",
			"notifications": "There are no any notifications.",
			"activities": "There are no any activities",
			"announcements": "There are no any announcements.",
			"defects": "There are no any defects.",
			"incidents": "There are no any incidents.",
			"meetings": "There are no any meetings.",
			"projects": "There are no any projects.",
			"tasks": "There are no any tasks.",
			"todos": "There are no any todos.",
			"roles": "There are no any roles.",
			"departments": "There are no any departments.",
			"holidays": "There are no any holidays.",
			"teams": "There are no any teams.",
			"users": "There are no any users.",
			"clients": "There are no any clients.",
			"attachments": "There are no any attachments.",
			"file_browser": "There are no any folders / files.",
			"custom_fields": "There are no any custom fields.",
			"database_backups": "There are no any database backups.",
			"translations": "There are no any translations.",
			"histories": "There are no any histories.",
			"articles": "There are no any articles.",
			"categories": "There are no any categories.",
			"providers": "There are no any providers.",
			"timesheet": "There are no any timesheet.",
			"sprints": "There are no any sprint."
		},
		"status": {
			"all": "All",
			"my": "My",
			"total": "Total",
			"open": "Open",
			"in_progress": "In Progress",
			"on_hold": "On Hold",
			"cancel": "Cancel",
			"completed": "Completed",
			"waiting": "Waiting",
			"assigned": "Assigned",
			"closed": "Closed",
			"solved": "Solved",
			"reopen": "Re-open",
			"deferred": "Deferred",
			"unpublished": "Unpublished",
			"published": "Published",
			"overdue": "Overdue",
			"Not Started": "Not Started",
			"In Progress": "In Progress",
			"On Hold": "On Hold",
			"active": "Active",
			"deactive": "Deactive",
			"inactive": "Inactive",
			"success": "Success",
			"error": "Error",
			"reserved": "Reserved",
			"confirmed": "Confirmed",
			"finished": "Finished",
			"canceled": "Canceled",
			"released": "Released"
		},
		"priorities": {
			"urgent": "Urgent",
			"very_high": "Very High",
			"high": "High",
			"medium": "Medium",
			"low": "Low"
		},
		"errors_keys": {
			"key1": "Access is denied",
			"key2": "Bad Request",
			"key3": "Unauthorized",
			"key4": "Access is denied",
			"key5": "Not Found",
			"key6": "Unprocessable Entity"
		},
		"error_messages": {
			"message1": "You may not have the appropriate permissions to access the file or resources.",
			"message2": "Unauthorized User - In case of Auth Token Expired.",
			"message3": "In case of Auth Token Expired.",
			"message4": "Sorry, but the page you were looking for could not be found.",
			"message5": "Something went is wrong!"
		},
		"placeholders":{
			"placeholder1": "-- Select Project --"
		}
	},
	"shared": {
		"inline_edit": {
			"placeholders": {
				"placeholder1": "Enter {{elementFor}}",
				"placeholder2": "-- Select {{elementFor}} --",
				"placeholder3": "Select {{elementFor}}"
			},
			"error_messages": {
				"message1": "{{elementFor}} is required.",
				"message2": "{{elementFor}} allow only digits, 2 digits after colon(less than 60) without any special characters.",
				"message3": "Please enter a valid {{elementFor}}.",
				"message4": "{{elementFor}} can be min {{minLength}} characters long."
			}
		},
		"show_custom_field": {
			"placeholders": {
				"placeholder1": "Enter {{custom_field}}",
				"placeholder2": "-- Select {{custom_field}} --"
			},
			"error_messages": {
				"message1": "Please enter a valid {{custom_field}}.",
				"message2": "Please select a valid {{custom_field}}."
			}	
		}
	},
	"months": [
		"Jan",
		"Feb",
		"Mar", 
		"Apr",
		"May",
		"Jun",
		"Jul",
		"Aug",
		"Sep",
		"Oct",
		"Nov",
		"Dec"
	],
	"customFieldsType": [
		{ "value": "text", "label": "Text" },
		{ "value": "textarea", "label": "Text Area" },
		{ "value": "dropdown", "label": "Drop Down" },
		{ "value": "date", "label": "Date" },
		{ "value": "checkbox", "label": "Checkbox" },
		{ "value": "numeric", "label": "Numeric" }
	],
	"breadcrumbs": {
		"dashboard": {
			"title": "Dashboard"
		},
		"announcements": {
			"title": "Announcements",
			"text": "Announcements"
		},
		"todos": {
			"title": "Todos",
			"text": "Todos"
		},
		"users": {
			"title": "Users",
			"text": "Users"
		},
		"departments": {
			"title": "Departments",
			"text": "Departments"
		},
		"roles": {
			"title": "Roles",
			"text": "Roles"
		},
		"mailbox": {
			"title": "Mailbox",
			"text": "Mailbox"
		},
		"file_browser": {
			"title": "File manager",
			"text": "File manager"
		},
		"teams": {
			"title": "Teams",
			"text": "Teams"
		},
		"timesheet": {
			"title": "Timesheet",
			"text": "Timesheet"
		},
		"holidays": {
			"title": "Holidays",
			"text": "Holidays"
		},
		"meetings": {
			"title": "Meetings",
			"text": "Meetings"
		},
		"clients": {
			"title": "Clients",
			"text": "Clients"
		},
		"email_templates": {
			"title": "Email Templates",
			"text": "Email Templates"
		},
		"settings": {
			"title": "Settings",
			"text": "Settings"
		},
		"calendar": {
			"title": "Calendar",
			"text": "Calendar"
		},
		"projects": {
			"title": "Projects",
			"text": "Projects"
		},
		"tasks": {
			"title": "Tasks",
			"text": "Tasks"
		},
		"defects": {
			"title": "Defects",
			"text": "Defects"
		},
		"incidents": {
			"title": "Incidents",
			"text": "Incidents"
		},
		"taskboard": {
			"title": "Task Board",
			"text": "Task Board"
		},
		"teamboard": {
			"title": "Team Board",
			"text": "Team Board"
		},
		"projects_planner": {
			"title": "Project Planner",
			"text": "Project Planner"
		},
		"knowledgebase": {
			"title": "Knowledgebase",
			"text": "Knowledgebase"
		},
		"category": {
			"title": "Category",
			"text": "Category"
		},
		"article": {
			"title": "Article",
			"text": "Article"
		},
		"reports": {
			"title": "Reports",
			"text": "Reports"
		},
		"appointments": {
			"title": "Appointments",
			"text": "Appointments"
		},
		"providers": {
			"title": "Providers",
			"text": "Providers"
		}
	},
	"dashboard": {
		"chart1": "Tasks Status",
		"chart2": "Project Status",
		"chart3": "Monthly Report"
	},
	"calendar": {
		"title": "Calendar",
		"today": "Today",
		"month": "Month",
		"week": "Week",
		"day": "Day"
	},
	"login": {
		"title": "Login",
		"fields":{
			"email": {
				"placeholder": "Username/Email",
				"errors": {
					"required": "Please enter a valid username or email address."
				}
			},
			"password": {
				"placeholder": "Password",
				"errors": {
					"required": "Please enter a valid password.",
					"email": "Password can be min 3 to max 30 character long."
				}
			}
		},
		"buttons": {
			"forgot_password": "Forgot password?",
			"register": {
				"text": "Do not have an account?",
				"button": "Register"
			},
			"admin_login": "Admin",
			"user_login": "User",
			"client_login": "Client"
		}
	},
	"register": {
		"title": "Register",
		"title1": "Already have an account?",
		"messages": {
			"success": "You have successfully registered."
		},
		"create": {
			"placeholders": {
				"placeholder1": "Enter Username",
				"placeholder2": "Enter First Name",
				"placeholder3": "Enter Last Name",
				"placeholder4": "Enter Email",
				"placeholder5": "Enter Password",
				"placeholder6": "Enter Confirm Password"
			},
			"error_messages": {
				"message1": "Please enter a valid username.",
				"message2": "Username can be min 3 to max 30 characters long without white spaces.",
				"message3": "Please enter a valid firstname.",
				"message4": "Please enter a valid lastname.",
				"message5": "Please enter a valid email.",
				"message6": "Email must be a valid email.",
				"message7": "Please enter a valid password.",
				"message8": "Password can be min 3 to max 30 character long.",
				"message9": "Please enter a valid confirm password.",
				"message10": "Password and confirm password does not match."
			}
		}
	},
	"forgot_password": {
		"title": "Forgot Password",
		"title1": "Please enter your email address below and we will send you information to change your password.",
		"messages": {
			"success": "Please check your email for instructions on how to reset your password."
		},
		"create": {
			"placeholders": {
				"placeholder1": "Enter Email"
			},
			"error_messages": {
				"message1": "Please enter a valid email.",
				"message2": "Email must be a valid email address."
			}
		}
	},
	"reset_password": {
		"title": "Reset Password",
		"messages": {
			"success": "Password reset successfully."
		},
		"create": {
			"placeholders": {
				"placeholder1": "Enter Password",
				"placeholder2": "Enter Confirm Password"
			},
			"error_messages": {
				"message1": "Please enter a valid password.",
				"message2": "Password can be min 3 to max 30 character long.",
				"message3": "Please enter a valid confirm password.",
				"message4": "Password and confirm password does not match.",
				"message5": "Please provide a valid email or token."
			}
		}
	},
	"announcements": {
		"title": "Announcements",
		"messages": {
			"create": "Announcement created successfully.",
			"update": "Announcement updated successfully.",
			"delete": "Announcement deleted successfully."
		},
		"columns": {
			"title": "Title",
			"creator": "Creator",
			"start_date": "Start Date",
			"end_date": "End Date",
			"status": "Status",
			"actions": "Actions"
		},
		"create": {
			"title1": "Create Announcement",
			"title2": "Edit Announcement",
			"fields": {
				"title": "Title",
				"status": "Status",
				"publish": "Publish",
				"unpublish": "Unpublish",
				"all_clients": "All Clients",
				"start_date": "Start Date",
				"end_date": "End Date",
				"description": "Description"
			},
			"placeholders": {
				"placeholder1": "Enter Announcement Title",
				"placeholder2": "Select Start Date",
				"placeholder3": "Select End Date"
			},
			"error_messages": {
				"message1": "Please enter a valid announcement title.",
				"message2": "Announcement title can be max 100 characters long.",
				"message3": "Please select a valid start date.",
				"message4": "Please select a valid end date."
			}
		}
	},
	"todos": {
		"title": "Todos",
		"messages": {
			"create": "Todo created successfully.",
			"update": "Todo updated successfully.",
			"delete": "Todo deleted successfully.",
			"status": "Status updated successfully."
		},
		"status": [
			{ "id": 1, "label": "open", "class": "open" },
			{ "id": 2, "label": "Completed", "class": "completed" }
		],
		"inline_edit": {
			"description": "Description",
			"due_date": "Due Date"
		},
		"create": {
			"fields": {
				"description": "Description",
				"status": "Status",
				"due_date": "Due Date"
			},
			"placeholders": {
				"placeholder1": "Enter Description",
				"placeholder2": "-- Select Status --",
				"placeholder3": "Select Due Date"
			},
			"error_messages": {
				"message1": "Please enter a valid description.",
				"message2": "Description can be max 5 characters long.",
				"message3": "Description can be max 255 characters long.",
				"message4": "Please select a valid todo status.",
				"message5": "Please select a valid due date."
			}
		}
	},
	"timesheet": {
		"title": "Timesheet",
		"title1": "My Timesheet",
		"title2": "All Timesheet",
		"messages": {
			"create": "Timesheet created successfully.",
			"update": "Timesheet updated successfully.",
			"delete": "Timesheet deleted successfully."
		},
		"inline_edit": {
			"note": "Note"
		},
		"columns": {
			"photo": "Photo",
			"entry": "Entry",
			"project_name": "Project",
			"user": "User",
			"start_time": "Start Time",
			"end_time": "End Time",
			"note": "Note",
			"time_h": "Hours",
			"actions": "Actions"
		},
		"range": {
			"today": "Today",
			"this_month": "This Month",
			"last_month": "Last Month",
			"this_week": "This Week",
			"last_week": "Last Week",
			"period": "Period"
		},
		"filter": {
			"error_messages": {
				"message1": "Please select a valid range.",
				"message2": "Please select a valid period from.",
				"message3": "Please select a valid period to."
			},
			"placeholders": {
				"placeholder1": "-- Select Project --",
				"placeholder2": "-- Select Client --",
				"placeholder3": "Select Period From",
				"placeholder4": "Select Period To",
				"placeholder5": "-- Select User --"
			}
		},
		"create": {
			"title1": "Create Timesheet",
			"title2": "Edit Timesheet",
			"fields": {
				"start_time": "Start Time",
				"end_time": "End Time",
				"note": "Note"
			},
			"placeholders": {
				"placeholder1": "Select Start Time",
				"placeholder2": "Select End Time",
				"placeholder3": "Enter Note"
			},
			"error_messages": {
				"message1": "Please select a valid start time.",
				"message2": "Please select a valid end time.",
				"message3": "Please select proper date and time for timesheet.",
				"message4": "Please enter a valid note."
			}
		}
	},
	"mailbox": {
		"title": "Mailbox",
		"title1": "Search mail ...",
		"title2": "Compose",
		"title3": "Compose Mail",
		"title4": "Inbox",
		"title5": "Sent",
		"title6": "Draft",
		"title7": "Favourite",
		"title8": "Trash",
		"title9": "Discard",
		"title10": "Send",
		"title11": "Attachments",
		"title12": "Refresh",
		"title13": "Mark as Read",
		"title14": "Mark as Favourite",
		"title15": "Move to Trash",
		"title16": "Move to Draft",
		"title17": "Discard Mail",
		"title18": "Mail View",
		"title19": "Folders",
		"title20": "mail",
		"messages": {
			"message": "File uploaded successfully.",
			"message1": "File deleted successfully.",
			"message2": "Please select atleat one mail.",
			"message3": "Mail send successfully.",
			"message4": "The mail moved to the Trash.",
			"message5": " mail moved to the trash.",
			"message6": "Mail unfavourited successfully.",
			"message7": " mails marked as favourite.",
			"message8": "Mails marked as read.",
			"message9": "The mail moved to the draft."
		},
		"compose": {
			"columns": {
				"name": "Name",
				"size": "Size",
				"progress": "Progress",
				"status": "Status",
				"actions": "Actions"
			},
			"fields": {
				"to": "To",
				"from": "From",
				"subject": "Subject"
			},
			"placeholders": {
				"placeholder1": "-- Select Users --",
				"placeholder2": "Enter Subject"
			},
			"error_messages": {
				"message1": "Please select a valid email address.",
				"message2": "Please enter a valid subject."
			}
		}
	},
	"roles": {
		"title": "Roles",
		"messages": {
			"create": "Role created successfully.",
			"update": "Role updated successfully.",
			"delete": "Role deleted successfully."
		},
		"columns": {
			"role_name": "Role Name",
			"role_slug": "Role Slug",
			"description": "description",
			"actions": "Actions"
		},
		"inline_edit": {
			"role_name": "Role Name",
			"description": "Description"
		},
		"create": {
			"fields": {
				"role_name": "Role Name",
				"role_slug": "Role Slug",
				"description": "Description"
			},
			"placeholders": {
				"placeholder1": "Enter Role Name",
				"placeholder2": "Enter Role Slug"
			},
			"error_messages": {
				"message1": "Please enter a valid role name.",
				"message2": "Role name can be max 50 characters long.",
				"message3": "Role slug can be max 50 characters long.",
				"message4": "Role slug can be only letters, underscore.",
				"message5": "Please enter a valid role slug."
			}
		}
	},
	"departments": {
		"title": "Departments",
		"title1": "Detail Department",
		"title2": "Department Role",
		"messages": {
			"create": "Department created successfully.",
			"update": "Department updated successfully.",
			"delete": "Department deleted successfully."
		},
		"columns": {
			"designation": "Designation",
			"actions": "Actions"
		},
		"create": {
			"fields": {
				"department_name": "Department Name",
				"department_roles": "Department Roles",
				"designation": "Designation",
				"department": "Department",
				"permission": "Permission"
			},
			"placeholders": {
				"placeholder1": "Enter Department Name"
			},
			"error_messages": {
				"message1": "Please enter a valid department name.",
				"message2": "Department name can be max 20 characters long.",
				"message3": "Please select a valid department roles."
			}
		},
		"tooltip": {
			"tooltip1": "Select All Permissions",
			"tooltip2": "If you select create/edit/delete no need to select the view",
			"tooltip3": "Select All Create",
			"tooltip4": "Select All Edit",
			"tooltip5": "Select All Delete",
			"tooltip6": "Select",
			"tooltip7": "Can create",
			"tooltip8": "Can Edit",
			"tooltip9": "Can Delete",
			"tooltip10": "Select All",
			"tooltip11": "Permission"
		}
	},
	"holidays": {
		"title": "Holidays",
		"messages": {
			"create": "Holiday created successfully.",
			"update": "Holiday updated successfully.",
			"delete": "Holiday deleted successfully."
		},
		"inline_edit": {
			"event_name": "Event Name",
			"holiday_date": "Holiday Date"
		},
		"columns": {
			"event_name": "Event Name",
			"date": "Date",
			"color": "Color",
			"actions": "Actions"
		},
		"create": {
			"fields": {
				"event_name": "Event Name",
				"date": "Date",
				"color": "Color",
				"location": "Location",
				"description": "Description"
			},
			"placeholders": {
				"placeholder1": "Enter Event Name",
				"placeholder2": "Enter Date",
				"placeholder3": "Enter Location"
			},
			"error_messages": {
				"message1": "Please enter a valid event name.",
				"message2": "Event name can be max 50 characters long.",
				"message3": "Please select a valid date."
			}
		}
	},
	"meetings": {
		"title": "Meetings",
		"details": {
			"title1": "Description"
		},
		"messages": {
			"create": "Meeting created successfully.",
			"update": "Meeting updated successfully.",
			"delete": "Meeting deleted successfully.",
			"status": "Status changed successfully."
		},
		"inline_edit": {
			"title": "Title"
		},
		"columns": {
			"id": "ID",
			"title": "Title",
			"organizer": "Organizer",
			"peoples": "Invite Peoples",
			"start": "Start",
			"end": "End",
			"hours": "Hours",
			"status": "Status",
			"actions": "Actions"
		},
		"create": {
			"fields": {
				"title": "Title",
				"start_date": "Start Date",
				"end_date": "End Date",
				"peoples": "Invite Peoples",
				"client_name": "Client Name",
				"location": "Location",
				"description": "Description",
				"status": "Status"
			},
			"placeholders": {
				"placeholder1": "Event Title",
				"placeholder2": "Select Start Date",
				"placeholder3": "Select End Date",
				"placeholder4": "-- Select Members --",
				"placeholder5": "Location",
				"placeholder6": "-- Select Client --",
				"placeholder7": "-- Select Status --"
			},
			"error_messages": {
				"message1": "Please enter a valid meeting title.",
				"message2": "Meeting title can be max 50 characters long.",
				"message3": "Please select a valid start date.",
				"message4": "Please select a valid end date.",
				"message5": "Please select a valid meeting members.",
				"message6": "Please select proper date and time for meeting.",
				"message7": "Please select a valid status."
			}
		},
		"status": [
			{ "id": 1, "label": "Open", "class": "open" },
			{ "id": 2, "label": "In Progress", "class": "in_progress" },
			{ "id": 3, "label": "Cancel", "class": "cancel" },
			{ "id": 4, "label": "Completed", "class": "completed" }
		]
	},
	"teams": {
		"title": "Teams",
		"title1": "Import Teams",
		"title2": "Team Leader",
		"title3": "Team Boards",
		"messages": {
			"create": "Team created successfully.",
			"update": "Team updated successfully.",
			"delete": "Team deleted successfully.",
			"import": "Team imported successfully."
		},
		"labels": {
			"label1": "Before start importing teams, make sure that you have imported users."
		},
		"inline_edit": {
			"team_name": "Team Name"
		},
		"columns": {
			"team_name": "Team Name",
			"members": "Members",
			"leader": "Leader",
			"actions": "Actions"
		},
		"create": {
			"fields": {
				"team_name": "Team Name",
				"members": "Members",
				"leader": "Leader",
				"description": "Description",
				"actions": "Actions",
				"csv_file": "Select team CSV file"
			},
			"placeholders": {
				"placeholder1": "Enter Team Name",
				"placeholder2": "-- Select Team Members --",
				"placeholder3": "-- Select Team Leader --"
			},
			"error_messages": {
				"message1": "Please enter valid team name.",
				"message2": "Team name can be max 30 characters long.",
				"message3": "Please select a valid team members.",
				"message4": "Please select a valid team leader.",
				"message5": "Please Select CSV file to import.",
				"message6": "This file is not a csv file. Please select only csv file."
 			}
		}
	},
	"users": {
		"title": "Users",
		"title1": "Create User",
		"title2": "Edit User",
		"title3": "User Permissions",
		"title4": "Change Email",
		"title5": "Change Password",
		"title6": "Import User",
		"title7": "Change Avatar",
		"headings": {
			"head1": "Personal Info",
			"head2": "Requirements",
			"head3": "About User",
			"head4": "Contact Info & Bio"
		},
		"messages": {
			"create": "User created successfully.",
			"update": "User updated successfully.",
			"delete": "User deleted successfully.",
			"import": "User imported successfully.",
			"status": "Status changed successfully.",
			"invite": "Invitation send successfully.",
			"email_change": "Email change request send successfully. Please check your inbox and change your email.",
			"password_change": "Password change request send successfully. Please check your inbox and change your email.",
			"avatar": "Avatar changed successfully."
		},
		"buttons": {
			"assign_permission": "Assign Permission Modal"
		},
		"create": {
			"fields": {
				"first_name": "First Name",
				"last_name": "Last Name",
				"email": "Email",
				"password": "Password",
				"confirm_password": "Confirm Password",
				"profile_photo": "Profile Photo",
				"departments_roles": "Departments / Roles",
				"assigned_to": "Assigned To",
				"userid": "User ID",
				"employment_ID": "Employment ID",
				"username": "Username",
				"skype": "Skype",
				"country": "Country",
				"language": "Language",
				"mobile": "Mobile",
				"phone": "Phone",
				"is_departmenthead": "Is he/she Department head?",
				"everyone": "Everyone",
				"customize_permission": "Customize Permission",
				"select_users": "Select Users",
				"old_password": "Old Password",
				"new_password": "New Password",
				"csv_file": "Select user CSV file",
				"gender": "Gender",
				"father_name": "Your Father Name",
				"mother_name": "Your Mother Name",
				"joining_date": "Joining Date",
				"dob": "Date of Birth",
				"marital_status": "Marital Status"
			},
			"placeholders": {
				"placeholder1": "Enter First Name",
				"placeholder2": "Enter Last Name",
				"placeholder3": "Enter Email",
				"placeholder4": "Enter Password",
				"placeholder5": "Enter Confirm Password",
				"placeholder6": "-- Select Departments/Roles --",
				"placeholder7": "Enter Username",
				"placeholder8": "Enter Skype",
				"placeholder9": "-- Select Country --",
				"placeholder10": "-- Select Language --",
				"placeholder11": "Enter Old Password"
			},
			"error_messages": {
				"message1": "Please enter a valid firstname.",
				"message2": "Firstname can be max 20 characters long.",
				"message3": "Please enter a valid lastname.",
				"message4": "Lastname can be max 20 characters long.",
				"message5": "Please enter a valid email.",
				"message6": "Email can be a valid email address.",
				"message7": "Please enter a valid password.",
				"message8": "Password can be min 3 to max 30 character long.",
				"message9": "Please enter a valid confirm password.",
				"message10": "Password and confirm password does not match.",
				"message11": "Drop files here or click to upload.",
				"message12": "Please select a valid departments with roles.",
				"message13": "Please select a valid user permissions.",
				"message14": "Please enter a valid user generated ID.",
				"message15": "Please enter a valid employment ID.",
				"message16": "Please enter a valid username.",
				"message17": "Username can be min 3 to max 30 characters long without white spaces.",
				"message18": "Please enter a valid mobile number.",
				"message19": "Please enter a valid phone number.",
				"message20": "Please select a valid user permissions.",
				"message21": "Please Select CSV file to import.",
				"message22": "This file is not a csv file. Please select only csv file."
			}
		},
		"inline_edit": {
			"first_name": "First Name",
			"last_name": "Last Name",
			"username": "Username",
			"gender": "Gender",
			"father_name": "Father Name",
			"mother_name": "Mother Name",
			"joining_date": "Joining Date",
			"dob": "DOB",
			"maritial_status": "Maritial Status",
			"country": "Country",
			"language": "Language",
			"skype": "Skype",
			"mobile": "Mobile",
			"phone": "Phone"
		},
		"profile": {
			"comments": "Comments",
			"activities": "Activities",
			"total_messages": "Total Messages",
			"articles": "Articles",
			"total_hours": "Total Hours",
			"task_hours": "Task Hours",
			"defect_hours": "Defect Hours",
			"incident_hours": "Incident Hours",
			"meeting_hours": "Meeting Hours",
			"leave_other_hours": "Leave/Other Hours",
			"projects": "Projects",
			"tasks": "Tasks",
			"defects": "Defects",
			"incidents": "Incidents"
		},
		"columns": {
			"photo": "Photo",
			"first_name": "First Name",
			"last_name": "Last Name",
			"username": "Username",
			"status": "Status",
			"departments_roles": "Departments/Roles",
			"actions": "Actions"
		}
	},
	"clients": {
		"title": "Clients",
		"title1": "Create Client",
		"title2": "Edit Client",
		"inline_edit": {
			"first_name": "First Name",
			"last_name": "Last Name"
		},
		"columns": {
			"photo": "Photo",
			"first_name": "First Name",
			"last_name": "Last Name",
			"username": "Username",
			"company_name": "Company Name",
			"company_email": "Company Email",
			"status": "Status",
			"departments_roles": "Departments/Roles",
			"actions": "Actions"
		},
		"headings": {
			"head1": "Personal Info",
			"head2": "Company Info",
			"head3": "Hosting Info",
			"head4": "About Client",
			"head5": "Contact Info & Bio",
			"head6": "Requirements",
			"head7": "Social Info"
		},
		"messages": {
			"create": "Client created successfully.",
			"update": "Client updated successfully.",
			"delete": "Client deleted successfully.",
			"status": "Status changed successfully.",
			"invite": "Invitation send successfully."
		},
		"create": {
			"fields": {
				"first_name": "First Name",
				"last_name": "Last Name",
				"email": "Email",
				"password": "Password",
				"confirm_password": "Confirm Password",
				"company_name": "Company Name",
				"company_email": "Company Email",
				"company_phone": "Company Phone",
				"company_mobile": "Company Mobile",
				"company_country": "Company Country",
				"company_city": "Company City",
				"zip_code": "Zip Code",
				"company_fax": "Company Fax",
				"company_website": "Company Website",
				"skype": "Skype",
				"company_address": "Company Address",
				"hosting_company": "Hosting Company",
				"username": "Username",
				"port": "Port",
				"client_ID": "Client ID",
				"country": "Country",
				"language": "Language",
				"mobile": "Mobile",
				"phone": "Phone",
				"profile_photo": "Profile Photo",
				"departments_roles": "Departments / Roles",
				"facebook_URL": "Facebook URL",
				"twitter_URL": "Twitter URL",
				"linkedIn_URL": "LinkedIn URL"
			},
			"placeholders": {
				"placeholder1": "Enter First Name",
				"placeholder2": "Enter Last Name",
				"placeholder3": "Enter Email",
				"placeholder4": "Enter Password",
				"placeholder5": "Enter Confirm Password",
				"placeholder6": "Enter Company Name",
				"placeholder7": "Enter Company Email",
				"placeholder8": "-- Select Company Country --",
				"placeholder9": "Enter Company City",
				"placeholder10": "Enter Company Zipcode",
				"placeholder11": "Enter Company Fax",
				"placeholder12": "Enter Company Website",
				"placeholder13": "Enter Company Skype",
				"placeholder14": "Enter Company Address",
				"placeholder15": "Enter Hosting Company",
				"placeholder16": "Enter Hosting Username",
				"placeholder17": "Enter Hosting Password",
				"placeholder18": "Enter Hosting Port",
				"placeholder19": "Enter Username",
				"placeholder20": "Enter Skype",
				"placeholder21": "-- Select Country --",
				"placeholder22": "-- Select Language --",
				"placeholder23": "-- Select Departments/Roles --",
				"placeholder24": "Enter Facebook URL",
				"placeholder25": "Enter Twitter URL",
				"placeholder26": "Enter LinkedIn URL"
			},
			"error_messages": {
				"message1": "Please enter a valid firstname.",
				"message2": "Firstname can be max 20 characters long.",
				"message3": "Please enter a valid lastname.",
				"message4": "Lastname can be max 20 characters long.",
				"message6": "Email can be a valid email address.",
				"message7": "Please enter a valid password.",
				"message8": "Password can be min 3 to max 30 character long.",
				"message9": "Please enter a valid confirm password.",
				"message10": "Password and confirm password does not match.",
				"message12": "Phone number must be a valid number.",
				"message14": "Mobile number must be a valid number.",
				"message15": "Please enter a valid company website.",
				"message17": "Please enter a valid client generated ID.",
				"message19": "Please enter a valid username.",
				"message20": "Username can be min 3 to max 30 characters long without white spaces.",
				"message21": "Please enter a valid mobile number.",
				"message22": "Please enter a valid phone number.",
				"message23": "Drop files here or click to upload.",
				"message24": "Please select a valid departments with roles."
			}
		}
	},
	"settings": {
		"title": "Settings",
		"messages": {
			"update": "Setting updated successfully."
		},
		"company_details": {
			"title": "Company Details",
			"create": {
				"fields": {
					"company_name": "Company Name",
					"legal_name": "Legal Name",
					"short_name": "Short Name",
					"company_email": "Company Email",
					"company_phone": "Company Phone",
					"company_website": "Company Website",
					"company_country": "Company Country",
					"city": "City",
					"company_zipcode": "Zip Code",
					"contact_person": "Contact Person",
					"company_address": "Company Address"
				},
				"placeholders": {
					"placeholder1": "Enter Company Name",
					"placeholder2": "Enter Legal Name",
					"placeholder3": "Enter Short Name",
					"placeholder4": "Enter Company Email",
					"placeholder5": "Enter Company Phone",
					"placeholder6": "Enter Company Website",
					"placeholder7": "-- Select Country --",
					"placeholder8": "Enter City",
					"placeholder9": "Enter Zip Code",
					"placeholder10": "Enter Contact Person",
					"placeholder11": "Enter Company Address"
				},
				"error_messages": {
					"message1": "Please enter a valid company name.",
					"message2": "Please enter a valid company legal name.",
					"message3": "Please enter a valid company short name.",
					"message4": "Company short name can be max 10 characters long.",
					"message5": "Please enter a valid company email.",
					"message6": "Email must be a valid email address.",
					"message7": "Company phone must be a valid phone number format.",
					"message8": "Company website must be a valid URL.",
					"message9": "Please enter a valid zipcode.",
					"message10": "Zipcode code can be allowed max 10 digits.",
					"message11": "Please enter a valid company address."
				}
			}
		},
		"cronjob": {
			"title": "Cronjob",
			"create": {
				"fields": {
					"active_cronjob": "Active Cronjob",
					"automatic_backup": "Automatic Backup Database",
					"last_cronjob_run": "Last Cronjob Run",
					"daily_report_emails": "Daily Report Emails"
				},
				"placeholders": {
					"placeholder1": "Enter Emails"
				},
				"messages": {
					"message1": "This enables the cronjob feature, if it is setup correctly.",
					"message2": "Creates a database backup every 7 days. Requires cronjob to be activated!"
				}
			}
		},
		"custom_fields": {
			"title": "Custom Fields",
			"title1": "custom field",
			"headings": {
				"head1": "Enter lable and value for dropdown box."
			},
			"columns": {
				"label": "Label",
				"value": "Value",
				"custom_field_for": "Custom Field For",
				"type": "Type",
				"status": "Status",
				"actions": "Actions"
			},
			"messages": {
				"create": "Custom field created successfully.",
				"update": "Custom field updated successfully.",
				"delete": "Custom field deleted successfully.",
				"status": "Status changed successfully."
			},
			"create": {
				"fields": {
					"custom_field_for": "Custom Field For",
					"field_label": "Field Label",
					"help_text": "Help Text",
					"is_required_field": "Is Required Field",
					"show_on_detail": "To Show On Detail Page",
					"field_type": "Field Type"
				},
				"placeholders": {
					"placeholder1": "-- Select Custom Field Form --",
					"placeholder2": "Enter Field Label",
					"placeholder3": "Enter Help Text",
					"placeholder4": "-- Select Field Type --"
					
				},
				"error_messages": {
					"message1": "Please select a valid custom field form.",
					"message2": "Please enter a valid field label.",
					"message3": "Please enter a valid help text.",
					"message4": "Please select a valid field type.",
					"message5": "Please enter a valid label.",
					"message6": "Please enter a valid value."
				}
			}
		},
		"dashboard_settings": {
			"title": "Dashboard Settings",
			"title1": "Announcements",
			"title2": "Recent Activities",
			"title3": "ToDos List",
			"title4": "Meetings",
			"title5": "Projects",
			"title6": "Tasks",
			"title7": "Defects",
			"title8": "Incidents",
			"columns": {
				"settings": "Settings"
			}
		},
		"database_backups": {
			"title": "Database Backups",
			"title1": "database backup",
			"title2": "Backup",
			"columns": {
				"date": "Date",
				"file_name": "File Name",
				"actions": "Actions"
			},
			"messages": {
				"create": "Database backup created successfully.",
				"delete": "Database backup deleted successfully.",
				"restore": "Database restored successfully."
			}
		},
		"email_notification" :{
			"title": "Email Notifications",
			"title1": "Announcement Email",
			"columns": {
				"notification": "Notification"
			}
		},
		"email_settings": {
			"title": "Email Settings",
			"create": {
				"fields": {
					"company_email": "Company Email",
					"email_protocol": "Email Protocol",
					"smtp_host": "SMTP Host",
					"smtp_user": "SMTP User",
					"smtp_password": "SMTP Password",
					"smtp_port": "SMTP Port",
					"email_encryption": "Email Encryption",
					"sparkpost_secret": "Sparkpost Secret",
					"mailgun_domain": "Mailgun Domain",
					"mailgun_secret": "Mailgun Secret",
					"mandrill_secret": "Mandrill Secret"
				},
				"placeholders": {
					"placeholder1": "Enter Company Email",
					"placeholder2": "-- Select Email Protocol --",
					"placeholder3": "Enter Password",
					"placeholder4": "-- Select Email Encryption --",
					"placeholder5": "Enter Sparkpost Secret",
					"placeholder6": "Enter Mailgun Domain",
					"placeholder7": "Enter Mailgun Secret",
					"placeholder8": "Enter Mandrill Secret"
				},
				"error_messages": {
					"message1": "Please enter a valid company email.",
					"message2": "Email must be a valid email address.",
					"message3": "Please select a valid email protocol.",
					"message4": "Please enter a valid SMTP host.",
					"message5": "Please enter a valid SMTP user.",
					"message6": "SMTP user must be a valid email address.",
					"message7": "Please enter a valid SMTP password.",
					"message8": "Please enter a valid SMTP port."
				}
			}
		},
		"email_templates": {
			"title": "Email Templates",
			"messages": {
				"update": "Email template updated successfully."
			},
			"create": {
				"fields": {
					"subject": "Subject",
					"body": "Body"
				},
				"placeholders": {
					"placeholder1": "-- Select Email group --",
					"placeholder2": "Enter Subject"
				}
			}
		},
		"menu_allocation": {
			"title": "Menu Allocation",
			"title1": "Expand All",
			"title2": "Collapse All",
			"messages": {
				"update": "Menu updated successfully."
			}
		},
		"system_settings": {
			"title": "System Settings",
			"create": {
				"fields": {
					"default_language": "Default Language",
					"default_locale": "Default Locale",
					"timezone": "Timezone",
					"tables_pagination_limit": "Tables Pagination Limit",
					"date_format": "Date Format",
					"time_format": "Time Format"
				},
				"placeholders": {
					"placeholder1": "-- Select Language --",
					"placeholder2": "-- Select Locale --",
					"placeholder3": "-- Select Timezone --",
					"placeholder4": "Enter Tables Pagination Limit",
					"placeholder5": "-- Select Date Format --",
					"placeholder6": "-- Select Time Format --"
				},
				"error_messages": {
					"message1": "Please enter a valid table pagination limit.",
					"message2": "Please select a valid date format.",
					"message3": "Please select a valid time format."
				}
			}
		},
		"system_update": {
			"title": "System Update",
			"create": {
				"fields": {
					"purchase_key": "Purchase Key",
					"buyer": "Buyer",
					"url": "URL"
				},
				"placeholders": {
					"placeholder1": "Enter Product Purchase Key",
					"placeholder2": "Enter Product Buyer Code"
				}
			}
		},
		"theme_settings": {
			"title": "Theme Settings",
			"create": {
				"fields": {
					"compnay_logo": "Company Logo",
					"login_background": "Login Background",
					"compnay_sidebar_logo": "Company Sidebar Logo",
					"sidebar_background_images": "Sidebar Background Images"
				},
				"error_messages": {
					"message1": "Drop files here or click to upload."
				}
			}
		},
		"translations": {
			"title": "Translations",
			"title1": "translation",
			"columns": {
				"icon": "Icon",
				"language": "Language",
				"status": "Status",
				"actions": "Actions"
			},
			"messages": {
				"create": "Translation created successfully.",
				"update": "Translation updated successfully.",
				"delete": "Translation deleted successfully.",
				"status": "Status changed successfully."
			},
			"create": {
				"fields": {
					"language": "Language",
					"icon": "Icon"
				},
				"placeholders": {
					"placeholder1": "-- Select Language --"
				},
				"error_messages": {
					"message1": "Please select a valid language.",
					"message2": "Please select a valid icon.",
					"message3": "Drop files here or click to upload."
				}
			}
		}
	},
	"file_browser": {
		"title": "File Manager",
		"title1": "Create Folder",
		"title2": "Edit Folder",
		"title3": "Upload Files",
		"title4": "Edit File",
		"title5": "file",
		"title6": "folder",
		"title7": "Added",
		"columns": {
			"name": "Name",
			"size": "Size",
			"progress": "Progress",
			"status": "Status",
			"actions": "Actions"
		},
		"messages": {
			"create_folder": "Folder created successfully.",
			"update_folder": "Folder updated successfully.",
			"delete_folder": "Folder deleted successfully.",
			"upload_file": "File uploaded successfully.",
			"update_file": "File updated successfully.",
			"delete_file": "File deleted successfully."
		},
		"create": {
			"fields": {
				"folder_name": "Folder Name",
				"description": "description",
				"file_name": "File Name"
			},
			"placeholders": {
				"placeholder1": "Enter Folder Name",
				"placeholder2": "Enter Description",
				"placeholder3": "Enter File Name"
			},
			"error_messages": {
				"message1": "Please enter a valid folder name.",
				"message2": "Drag here to upload...",
				"message3": "Please enter a valid file name."
			}
		}
	},
	"activities": {
		"title": "Activities",
		"columns": {
			"activity": "Activity",
			"date": "Date",
			"username": "Username",
			"module": "Module",
			"photo": "Photo",
			"description": "Description"
		}
	},
	"projects": {
		"title": "Projects",
		"title1": "Create Project",
		"title2": "Edit Project",
		"title3": "Import Project",
		"headings": {
			"head1": "Project Info",
			"head2": "Project Dates",
			"head3": "Other Info",
			"head4": "Custom Fields",
			"head5": "Description"
		},
		"messages": {
			"create": "Project created successfully.",
			"update": "Project updated successfully.",
			"delete": "Project deleted successfully.",
			"import": "Project imported successfully.",
			"status": "Status changed successfully."
		},
		"status": [
			{ "id": 1, "label": "Open", "class": "open" },
			{ "id": 2, "label": "In Progress", "class": "in_progress" },
			{ "id": 3, "label": "On Hold", "class": "on_hold" },
			{ "id": 4, "label": "Cancel", "class": "cancel" },
			{ "id": 5, "label": "Completed", "class": "completed" }
		],
		"billing_types": [
			{ "id": 1, "label": "Fixed Rate", "value": 1 },
			{ "id": 2, "label": "Hourly Rate", "value": 2 }
		],
		"columns": {
			"id": "ID",
			"project_name": "Project Name",
			"progress": "Progress",
			"start": "Start",
			"end": "End",
			"status": "Status",
			"logo": "Logo",
			"duration": "Duration",
			"completion": "Completion",
			"creator": "Creator",
			"assigned_To": "Assigned To",
			"version": "Version",
			"billing": "Billing",
			"budget": "Budget",
			"hours": "Hours",
			"client": "Client",
			"actions": "Actions"
		},
		"budges": {
			"version": "Version",
			"current_version": "Current Version",
			"completion": "Completion with",
			"csv_file": "Before start importing projects, make sure that you have imported users and their teams."
		},
		"inline_edit": {
			"project_name": "Project Name",
			"client": "Client",
			"start_date": "Start Date",
			"end_date": "End Date",
			"estimate_hours": "Estimate Hours",
			"demo_url": "Demo URL",
			"billing_type": "Billing Type",
			"budget": "Budget",
			"description": "Description",
			"notes": "Notes"
		},
		"details": {
			"title1": "Custom Fields",
			"title2": "Description"
		},
		"create": {
			"fields": {
				"project_id": "Project ID",
				"project_name": "Project Name",
				"version": "Version",
				"client_name": "Client Name",
				"status": "Status",
				"assigned_group": "Assigned Group",
				"demo_URL": "Demo URL",
				"assigned_To": "Assigned To",
				"start_date": "Start Date",
				"end_date": "End Date",
				"estimate_hours": "Estimate Hours",
				"billing_type": "Billing Type",
				"fixed_price": "Fixed Price($)",
				"hourly_rate": "Hourly Rate($)",
				"project_hours": "Auto Progress",
				"progress": "Progress",
				"project_logo": "Project Logo",
				"description": "Description",
				"budget": "Budget",
				"completed": "Completed",
				"created_by": "Created By",
				"csv_file": "Select project CSV file",
				"actual_hours":"Actual Hours"
			},
			"placeholders": {
				"placeholder1": "Enter Project Name",
				"placeholder2": "Enter Version",
				"placeholder3": "-- Select Client --",
				"placeholder4": "-- Select Status --",
				"placeholder5": "-- Select Assigned Group --",
				"placeholder6": "Unassigned",
				"placeholder7": "Select Start Date",
				"placeholder8": "Select End Date",
				"placeholder9": "-- Select Billing Type --",
				"placeholder10": "Enter Price Rate",
				"placeholder11": "Enter Description"
			},
			"error_messages": {
				"message1": "Please enter an unique and valid project name.",
				"message2": "Project name can be max 255 characters long.",
				"message3": "Please enter an unique and valid project version.",
				"message4": "Project version can be like 1.0 .",
				"message6": "Please select a valid status.",
				"message8": "Please enter a valid demo url.",
				"message9": "Please select a valid start date.",
				"message10": "Please select a valid end date.",
				"message11": "Estimate hours allow only digits, 2 digits after colon(less than 60) without any special characters.",
				"message12": "Fixed price must be grater then 0.",
				"message13": "Fixed price allow only digits.",
				"message14": "Drop files here or click to upload.",
				"message15": "This file is not a csv file. Please select only csv file.",
				"message16": "Please Select CSV file to import."
			}
		}
	},
	"project_planner": {
		"title": "Project Planner",
		"title1": "Create Task",
		"title2": "Create Story",
		"title3": "Create Sprint",
		"title4": "Project",
		"title5": "Sprint",
		"columns": {
			"name": "Name",
			"responsible": "Responsible",
			"type": "Type",
			"start_date": "Start Date",
			"end_date": "End Date",
			"hours": "Hours",
			"status": "Status",
			"actions": "Actions",
			"sprint": "Sprint",
			"creator": "Creator",
			"progress": "Progress"
		},
		"sprint": {
			"title" : "Sprint",
			"title1" : "Create Sprint",
			"title2" : "Edit Sprint",
			"messages": {
				"create": "Sprint created successfully.",
				"update": "Sprint updated successfully.",
				"delete": "Sprint deleted successfully."
			},
			"status": [
				{ "id": 1, "label": "Open", "class": "open" },
				{ "id": 2, "label": "In Progress", "class": "in_progress" },
				{ "id": 3, "label": "Closed", "class": "closed" }
			],
			"create": {
				"fields": {
					"sprint_name": "Project Sprint Name",
					"responsible": "Responsible",
					"start_date": "Start Date",
					"end_date": "End Date",
					"status": "Status",
					"estimate_hours": "Estimate Hours",
					"description": "Description"
				},
				"placeholders": {
					"placeholder1": "Enter Project Sprint Name",
					"placeholder2": "-- Select User --",
					"placeholder3": "Select Start Date",
					"placeholder4": "Select End Date",
					"placeholder5": "-- Select Status --",
					"placeholder6": "Enter Description"
				},
				"error_messages": {
					"message1": "Please enter an unique and valid sprint name.",
					"message2": "Sprint name can be max 255 characters long.",
					"message4": "Please select a valid start date.",
					"message5": "Please select a valid end date.",
					"message6": "Please select a valid status.",
					"message7": "Estimate hours allow only digits, 2 digits after colon(less than 60) without any special characters.",
					"message8": "Estimate hours must be less than "
				}
			}
		},
		"sprint_task": {
			"title": "Sprint Task",
			"title1": "Create Sprint",
			"title2": "Edit Sprint",
			"title3": "name",
			"title4": "Task",
			"title5": "Story",
			"messages": {
				"create": " created successfully.",
				"update": " updated successfully.",
				"task_delete": "Sprint task deleted successfully.",
				"story_delete": "Sprint story deleted successfully.",
				"released": "Task released successfully."
			},
			"task_status": [
				{ "id": 1, "label": "Open", "class": "open" },
				{ "id": 2, "label": "On Hold", "class": "on_hold" },
				{ "id": 3, "label": "Closed", "class": "closed" },
				{ "id": 4, "label": "Released", "class": "completed" }
			],
			"story_status": [
				{ "id": 1, "label": "Open", "class": "open" },
				{ "id": 2, "label": "In Progress", "class": "in_progress" },
				{ "id": 3, "label": "Closed", "class": "closed" }
			],
			"create": {
				"fields": {
					"name": "Name",
					"responsible": "Responsible",
					"start_date": "Start Date",
					"end_date": "End Date",
					"status": "Status",
					"estimate_hours": "Estimate Hours",
					"description": "Description"
				},
				"placeholders": {
					"placeholder1": "Enter Project Sprint ",
					"placeholder2": "-- Select User --",
					"placeholder3": "Select Start Date",
					"placeholder4": "Select End Date",
					"placeholder5": "-- Select Status --",
					"placeholder6": "Enter Description",
					"placeholder7": "Enter Project "
				},
				"error_messages": {
					"message1": "Please enter an unique and valid ",
					"message2": " name can be max 255 characters long.",
					"message3": "Please select a valid users.",
					"message4": "Please select a valid start date.",
					"message5": "Please select a valid end date.",
					"message6": "Please select a valid status.",
					"message7": "Estimate hours allow only digits, 2 digits after colon(less than 60) without any special characters.",
					"message8": "Estimate hours must be less than "
				}
			}
		},
		"move_sprint_task": {
			"title": "Move Task",
			"messages": {
				"move": " Task moved successfully."
			},
			"create": {
				"fields": {
					"project": "Project",
					"sprint": "Sprint"
				},
				"placeholders": {
					"placeholder1": "-- Select Project --",
					"placeholder2": "-- Select Project Sprint --"
				},
				"error_messages": {
					"message1": "Please select a valid project.",
					"message2": "Please select a valid project sprint."
				}
			}
		}
	},
	"tasks": {
		"title": "Tasks",
		"title1": "Create Task",
		"title2": "Create Sub Task",
		"title3": "Import Tasks",
		"title4": "Edit Task",
		"title5": "Edit Sub Task",
		"headings": {
			"head1": "Task Info",
			"head2": "Task Dates",
			"head3": "Progress",
			"head4": "Project Info",
			"head5": "Requirements",
			"head6": "Custom Fields",
			"head7": "Description"
		},
		"messages": {
			"create": "Task created successfully.",
			"update": "Task updated successfully.",
			"delete": "Task deleted successfully.",
			"import": "Task imported successfully.",
			"status": "Status changed successfully.",
			"priority": "Priority changed successfully."
		},
		"status": [
			{ "id": 1, "label": "Open", "class": "open" },
			{ "id": 2, "label": "In Progress", "class": "in_progress" },
			{ "id": 3, "label": "On Hold", "class": "on_hold" },
			{ "id": 4, "label": "Waiting", "class": "waiting" },
			{ "id": 5, "label": "Cancel", "class": "cancel" },
			{ "id": 6, "label": "Completed", "class": "completed" }
		],
		"status1": [
			{ "id":1, "label": "Open", "class": "open" },
			{ "id":2, "label": "In Progress", "class": "in_progress" },
			{ "id":5, "label": "Cancel", "class": "cancel" }
		],
		"status2": [
			{ "id":2, "label": "In Progress", "class": "in_progress" },
			{ "id":3, "label": "On Hold", "class": "on_hold" },
			{ "id":4, "label": "Waiting", "class": "waiting" },
			{ "id":5, "label": "Cancel", "class": "cancel" },
			{ "id":6, "label": "Completed", "class": "completed" }
		],
		"status3": [
			{ "id":2, "label": "In Progress", "class": "in_progress" },
			{ "id":3, "label": "On Hold", "class": "on_hold" },
			{ "id":5, "label": "Cancel", "class": "cancel" }
		],
		"status4": [
			{ "id":2, "label": "In Progress", "class": "in_progress" },
			{ "id":4, "label": "Waiting", "class": "waiting" },
			{ "id":5, "label": "Cancel", "class": "cancel" }
		],
		"status5": [
			{ "id":2, "label": "In Progress", "class": "in_progress" },
			{ "id":5, "label": "Cancel", "class": "cancel" }
		],
		"status6": [
			{ "id":1, "label": "Open", "class": "open" },
			{ "id":2, "label": "In Progress", "class": "in_progress" },
			{ "id":5, "label": "Cancel", "class": "cancel" },
			{ "id":6, "label": "Completed", "class": "completed" }
		],
		"parent_status1": [
			{ "id": 1, "label": "Open" }
		],
		"parent_status2": [
			{ "id": 2, "label": "In Progress"},
			{ "id": 3, "label": "On Hold"},
			{ "id": 4, "label": "Waiting"},
			{ "id": 5, "label": "Cancel"},
			{ "id": 6, "label": "Completed"}
		],
		"parent_status3": [
			{ "id": 2, "label": "In Progress"},
			{ "id": 3, "label": "On Hold"},
			{ "id": 5, "label": "Cancel"}
		],
		"parent_status4": [
			{ "id": 2, "label": "In Progress"},
			{ "id": 4, "label": "Waiting"},
			{ "id": 5, "label": "Cancel"}
		],
		"parent_status5": [
			{ "id": 5, "label": "Cancel"},
			{ "id": 2, "label": "In Progress"}
		],
		"parent_status6": [
			{"id": 1, "label": "Open"},
			{"id": 2, "label": "In Progress"},
			{"id": 4, "label": "Cancel"}
		],
		"priorities": [
			{ "id": 1, "label": "Urgent", "class": "urgent" },
			{ "id": 2, "label": "Very High", "class": "very_high" },
			{ "id": 3, "label": "High", "class": "high" },
			{ "id": 4, "label": "Medium", "class": "medium" }, 
			{ "id": 5, "label": "Low", "class": "low" }
		],
		"tooltips": {
			"sub_task": "Create Sub Task"
		},
		"budges": {
			"completion": "Completion with",
			"csv_file": "Before start importing projects, make sure that you have imported users and their teams."
		},
		"inline_edit": {
			"task_name": "Task Name",
			"estimate_hours": "Estimate Hours",
			"description": "Description",
			"notes": "Notes"
		},
		"details": {
			"title1": "Custom Fields",
			"title2": "Description",
			"title3": "Sub Task"
		},
		"columns": {
			"task_name": "Task Name",
			"id": "ID",
			"progress": "Progress",
			"start": "Start",
			"end": "End",
			"status": "Status",
			"task_ID": "Task ID",
			"start_date": "Start Date",
			"end_date": "End Date",
			"hours": "Hours",
			"priority": "Priority",
			"creator": "Creator",
			"assigned": "Assigned",
			"project": "Project",
			"project_version": "Project Version",
			"planned_start": "Planned Start",
			"planned_end": "Planned End",
			"actual_start": "Actual Start",
			"actual_end": "Actual End",
			"actions": "Actions"
		},
		"create": {
			"fields": {
				"task_name": "Task Name",
				"task_ID": "Task ID",
				"subtask_Id": "Subtask Id",
				"plan_startdate": "Plan Start Date",
				"plan_enddate": "Plan End Date",
				"actual_startdate": "Start Date",
				"actual_enddate": "End Date",
				"progress": "Progress",
				"project_name": "Project Name",
				"project_version": "Project Version",
				"parent_taskID": "Parent Task ID",
				"assigned_To": "Assigned To",
				"status": "Status",
				"priority": "Priority",
				"estimate_hours": "Estimate Hours",
				"actual_hours": "Actual Hours",
				"description": "Description",
				"csv_file": "Select Task CSV file",
				"created_by": "Created By",
				"completed": "Completed"
			},
			"placeholders": {
				"placeholder1": "Enter Task Name",
				"placeholder2": "Select Planned Start Date",
				"placeholder3": "Select Planned End Date",
				"placeholder4": "Select Start Date",
				"placeholder5": "Select End Date",
				"placeholder6": "-- Select Project Name --",
				"placeholder7": "-- Select Project Version --",
				"placeholder8": "-- Select Assign To --",
				"placeholder9": "-- Select Status --",
				"placeholder10": "-- Select Priority --"
			},
			"error_messages": {
				"message1": "Please enter an unique and valid task name.",
				"message2": "Task name can be max 255 characters long.",
				"message3": "Please enter an unique and valid task generated ID.",
				"message4": "Please select a valid planned start date.",
				"message5": "Please select a valid planned end date.",
				"message6": "Please select a valid start date.",
				"message7": "Please select a valid end date.",
				"message8": "Please select a valid project name.",
				"message11": "Please select a valid task status.",
				"message12": "Please select a valid task priority.",
				"message13": "Estimate hours allow only digits, 2 digits after colon(less than 60) without any special characters.",
				"message14": "Estimate hours must be less than",
				"message15": "Please Select CSV file to import."
			}
		}
	},
	"defects": {
		"title": "Defects",
		"title1": "Create Defect",
		"title2": "Edit Defect",
		"headings": {
			"head1": "Defect Info",
			"head2": "Project Info",
			"head3": "Upload Files",
			"head4": "Custom Fields",
			"head5": "Description"
		},
		"messages": {
			"create": "Defect created successfully.",
			"update": "Defect updated successfully.",
			"delete": "Defect deleted successfully.",
			"status": "Status changed successfully.",
			"severity": "Severity updated successfully."
		},
		"inline_edit": {
			"defect_name": "Defect Name",
			"defect_type": "Defect Type",
			"description": "Description",
			"notes": "Notes",
			"estimate_hours": "Estimate Hours"
		},
		"details": {
			"title1": "Custom Fields",
			"title2": "Description"
		},
		"tooltips": {
			"attachment": "Attachment"
		},
		"status": [   
			{ "id":1, "label": "Assigned", "class": "assigned" },
			{ "id":2, "label": "Closed", "class": "closed" },  
			{ "id":3, "label": "In Progress", "class": "in_progress" },
			{ "id":4, "label": "Open", "class": "open" },   
			{ "id":5, "label": "Solved", "class": "solved" },
			{ "id":6, "label": "Re-open", "class": "reopen" },
			{ "id":7, "label": "Deferred", "class": "deferred" }
		],
		"severities": [
			{ "id":1, "label": "Low", "class": "low" },
			{ "id":2, "label": "Medium", "class": "medium" },
			{ "id":3, "label": "High", "class": "high" },
			{ "id":4, "label": "Very High", "class": "very_high" },
			{ "id":5, "label": "Urgent", "class": "urgent" }
		],
		"defect_types": [
			{ "id": 1, "label": "Defects", "value": 1 },
			{ "id": 2, "label": "Enhancement", "value": 2 }
		],
		"columns": {
			"id": "ID",
			"defect_name": "Defect Name",
			"start_date": "Start Date",
			"end_date": "End Date",
			"actual_hours": "Hours",
			"type": "Type",
			"severity": "Severity",
			"status": "Status",
			"assigned": "Assigned",
			"creator": "Creator",
			"project": "Project",
			"project_version": "Project Version",
			"assigned_group": "Assigned Group",
			"actions": "Actions"
		},
		"create": {
			"fields": {
				"defect_ID": "Defect ID",
				"defect_name": "Defect Name",
				"start_date": "Start Date",
				"end_date": "End Date",
				"estimate_hours": "Estimate Hours",
				"actual_hours": "Actual Hours",
				"defect_type": "Defect Type",
				"status": "Status",
				"severity": "Severity",
				"assigned_group": "Assigned Group",
				"assigned_To": "Assigned To",
				"project_name": "Project Name",
				"project_version": "Project Version",
				"choose_file": "Select File",
				"description": "Description",
				"created_by": "Created By",
				"completed": "Completed"
			},
			"placeholders": {
				"placeholder1": "Enter Defect Name",
				"placeholder2": "-- Select Defect Type --",
				"placeholder3": "-- Select Defect Status --",
				"placeholder4": "-- Select Defect Severity --",
				"placeholder5": "-- Select Assigned Group --",
				"placeholder6": "-- Select Project --",
				"placeholder7": "-- Select Project Version --",
				"placeholder8": "Select Start Date",
				"placeholder9": "Select End Date"
			},
			"error_messages": {
				"message1": "Please enter an unique and valid defect name.",
				"message2": "Defect name can be max 255 characters long.",
				"message3": "Please enter an unique and valid defect generated ID.",
				"message4": "Please select a valid defect type.",
				"message5": "Please select a valid defect status.",
				"message6": "Please select a valid defect severity.",
				"message8": "Please select a valid project.",
				"message10": "Drop files here or click to upload.",
				"message11": "Please select a valid start date.",
				"message12": "Please select a valid end date.",
				"message13": "Estimate hours allow only digits, 2 digits after colon(less than 60) without any special characters."
			}
		}
	},
	"incidents": {
		"title": "Incidents",
		"title1": "Create Incident",
		"title2": "Edit Incident",
		"headings": {
			"head1": "Incident Info",
			"head2": "Project Info",
			"head3": "Custom Fields",
			"head4": "Description",
			"head5": "Other Info"
		},
		"messages": {
			"create": "Incident created successfully.",
			"update": "Incident updated successfully.",
			"delete": "Incident deleted successfully.",
			"import": "Incident imported successfully.",
			"status": "Status changed successfully.",
			"priority": "Priority changed successfully."
		},
		"inline_edit": {
			"incident_name": "Incident Name",
			"incident_type": "Incident Type",
			"description": "Description",
			"notes": "Notes",
			"estimate_hours": "Estimate Hours"
		},
		"details": {
			"title1": "Custom Fields",
			"title2": "Description"
		},
		"status": [   
			{ "id":1, "label": "Open", "class": "open" },  
			{ "id":2, "label": "Assigned", "class": "assigned" },  
			{ "id":3, "label": "In Progress", "class": "in_progress" },
			{ "id":4, "label": "Solved", "class": "solved" },    
			{ "id":5, "label": "Deferred", "class": "deferred" },
			{ "id":6, "label": "Re-open", "class": "reopen" },
			{ "id":7, "label": "Closed", "class": "closed" } 
		],
		"priorities": [
			{ "id":1, "label": "Low", "class": "low" },
			{ "id":2, "label": "Medium", "class": "medium" },
			{ "id":3, "label": "High", "class": "high" },
			{ "id":4, "label": "Very High", "class": "very_high" },
			{ "id":5, "label": "Urgent", "class": "urgent" }
		],
		"incident_types": [
			{ "id": 1, "label": "Service Request", "value": 1 },
			{ "id": 2, "label": "Info Request", "value": 2 }
		],
		"columns": {
			"id": "ID",
			"incident_name": "Incident Name",
			"start_date": "Start Date",
			"end_date": "End Date",
			"actual_hours": "Hours",
			"status": "Status",
			"assigned": "Assigned",
			"priority": "Priority",
			"creator": "Creator",
			"type": "Type",
			"project": "Project",
			"client": "Client",
			"client_company": "Client Company",
			"assigned_group": "Assigned Group",
			"actions": "Actions",
			"project_version": "Project Version"
		},
		"create": {
			"fields": {
				"incident_ID": "Incident ID",
				"incident_name": "Incident Name",
				"estimate_hours": "Estimate Hours",
				"actual_hours": "Actual Hours",
				"start_date": "Start Date",
				"end_date": "End Date",
				"status": "Status",
				"priority": "Priority",
				"incident_type": "Incident Type",
				"assigned_group": "Assigned Group",
				"assigned_To": "Assigned To",
				"project": "Project",
				"project_version": "Project Version",
				"description": "Description",
				"created_by": "Created By",
				"project_name": "Project Name"
			},
			"placeholders": {
				"placeholder1": "Enter Incident Name",
				"placeholder2": "-- Select Incident Status --",
				"placeholder3": "-- Select Incident Priority --",
				"placeholder4": "-- Select Incident Type --",
				"placeholder5": "-- Select Assigned Group --",
				"placeholder6": "-- Select Project --",
				"placeholder7": "-- Select Project Version --",
				"placeholder8": "Select Start Date",
				"placeholder9": "Select End Date"
			},
			"error_messages": {
				"message1": "Please enter an unique and valid incident name.",
				"message2": "Incident name can be max 255 characters long.",
				"message3": "Please enter an unique and valid incident generated ID.",
				"message4": "Please select a valid incident status.",
				"message5": "Please select a valid incident priority.",
				"message6": "Please select a valid incident type.",
				"message7": "Please select a valid start date.",
				"message8": "Please select a valid end date.",
				"message9": "Estimate hours allow only digits, 2 digits after colon(less than 60) without any special characters."
			}
		}
	},
	"attachments": {
		"title": "Attachments",
		"messages": {
			"create": "Attachment created successfully.",
			"delete": "Attachment deleted successfully."
		},
		"columns": {
			"file_title": "File Title",
			"attachment": "Attachment",
			"actions": "Actions"
		},
		"create": {
			"fields": {
				"file_title": "File Title",
				"attachment": "Attachment",
				"description": "Description"
			},
			"placeholders": {
				"placeholder1": "Enter File Title"
			},
			"error_messages": {
				"message1": "Please enter an unique and valid file title.",
				"message2": "Drop files here or click to upload.",
				"message3": "Please select a valid file.",
				"message4": "Please enter a valid comment.",
				"message5": "File title can be max 255 characters long."
			}
		}
	},
	"comments": {
		"title": "Comments",
		"messages": {
			"create": "Comment created successfully.",
			"update": "Comment updated successfully.",
			"delete": "Comment deleted successfully."
		},
		"buttons": {
			"button1": "Add Comment",
			"button2": "Reply"
		},
		"create": {
			"placeholders": {
				"placeholder1": "Enter Comment",
				"placeholder2": "Reply Comment"
			},
			"error_messages": {
				"message1": "Please enter a valid comment.",
				"message2": "Drop files here or click to upload."
			}
		}
	},
	"notes": {
		"title": "Notes",
		"messages": {
			"update": "Notes updated successfully."
		},
		"create": {
			"fields": {
				"notes": "Notes"
			}
		}
	},
	"histories": {
		"title": "Histories",
		"columns": {
			"creator": "Creator",
			"activity": "Activity",
			"updated_by": "Updated By",
			"solved_by": "Solved By",
			"closed_by": "Closed By",
			"commentor": "Commentor",
			"date": "Date"
		}
	},
	"task_boards": {
		"title": "Task Kanban",
		"headings": {
			"head1": "To Dos",
			"head2": "In Progress",
			"head3": "Completed"
		}
	},
	"knowledge_base": {
		"title": "Knowledge Base",
		"article": {
			"title": "Article",
			"title1": "Articles",
			"title2": "article",
			"columns": {
				"article_name": "Article Title",
				"actions": "Actions"
			},
			"messages": {
				"create": "Article created successfully.",
				"update": "Article updated successfully.",
				"delete": "Article deleted successfully."
			},
			"create": {
				"fields": {
					"article_title": "Article Title",
					"category": "Category",
					"file": "File",
					"description": "Description"
				},
				"placeholders": {
					"placeholder1": "Enter Article Title",
					"placeholder2": "-- Select Category --",
					"placeholder3": "Enter Description"
				},
				"error_messages": {
					"message1": "Please enter a valid article title.",
					"message2": "Article title can be max 50 characters long.",
					"message3": "Please select a valid category.",
					"message4": "Drop files here or click to upload.",
					"message5": "Please enter a valid description."
				}
			}
		},
		"category": {
			"title": "Category",
			"title1": "category",
			"title2": "Categories",
			"messages": {
				"create": "Category created successfully.",
				"update": "Category updated successfully.",
				"delete": "Category deleted successfully."
			},
			"create": {
				"fields": {
					"category_name": "Category Name",
					"category_logo": "Category Logo"
				},
				"placeholders": {
					"placeholder1": "Enter Category Name",
					"placeholder2": "Search .."
				},
				"error_messages": {
					"message1": "Please enter a valid category name.",
					"message2": "Category name can be max 50 characters long.",
					"message3": "Drop files here or click to upload.",
					"message4": "Please select a valid file."
				}
			}
		}
	},
	"reports": {
		"headings": {
			"project_report": "Project",
			"task_report": "Task",
			"defect_report": "Defect",
			"incident_report": "Incident"
		}
	},
	"appointments": {
		"title": "Appointments",
		"messages": {
			"create": "Appointment created successfully.",
			"edit": "Appointment edited successfully.",
			"delete": "Appointment deleted successfully.",
			"status": "Status changed successfully."
		},
		"budges": {
			"title1": "Booked Slots"
		},
		"status": [
			{ "id": 1, "label": "Reserved", "class": "open" },
			{ "id": 2, "label": "Confirmed", "class": "assigned" },
			{ "id": 3, "label": "Finished", "class": "completed" },
			{ "id": 4, "label": "Canceled", "class": "cancel" }
		],
		"columns": {
			"id": "ID",
			"title": "Title",
			"requester": "Requester",
			"start_time": "Start Time",
			"end_time": "End Time",
			"provider": "Provider",
			"status": "Status",
			"location": "Location",
			"actions": "Actions"
		},
		"create": {
			"fields": {
				"provider": "Provider",
				"start_date": "Start Date",
				"end_date": "End Date",
				"title": "Title",
				"client": "Client",
				"location": "Location",
				"attendees": "Attendees",
				"notes": "Notes",
				"status": "Status"
			},
			"placeholders": {
				"placeholder1": "Select Start Date",
				"placeholder2": "Select End Date",
				"placeholder3": "Enter Appointment Title",
				"placeholder4": "-- Select Client --",
				"placeholder5": "Location",
				"placeholder6": "Attendees",
				"placeholder7": "-- Select Status --",
				"placeholder8": "Search For Month"
			},
			"error_messages": {
				"message1": "Please select a valid provider.",
				"message2": "Please select a valid start date.",
				"message3": "Please select a valid end date.",
				"message4": "Please enter a valid appointment title.",
				"message5": "Appointment title can be max 255 characters long.",
				"message6": "Please select a valid client.",
				"message7": "Please enter a valid attendees.",
				"message8": "Please selct a valid status.",
				"message9": "Please select a valid provider."
			}
		}
	},
	"providers": {
		"title": "Providers",
		"messages": {
			"create": "Provider created successfully.",
			"edit": "Provider edited successfully.",
			"delete": "Provider deleted successfully."
		},
		"columns": {
			"firstname": "Firstname",
			"lastname": "Lastname",
			"color": "Color",
			"email": "Email",
			"actions": "Actions"
		},
		"create": {
			"fields": {
				"first_name": "First Name",
				"last_name": "Last Name",
				"email": "Email",
				"color": "Color"
			},
			"placeholders": {
				"placeholder1": "Enter Firstname",
				"placeholder2": "Enter Lastname",
				"placeholder3": "Enter Email"
			},
			"error_messages": {
				"message1": "Please enter a valid firstname.",
				"message2": "Firstname can be max 50 characters long.",
				"message3": "Please enter a valid lastname.",
				"message4": "Lastname can be max 50 characters long.",
				"message5": "Please enter a valid email.",
				"message6": "Email must be a valid email.",
				"message7": "Please select a valid color."
			}
		}
	},
	"kanban_board": {
		"title1": "Defect Kanban",
		"title2": "Incident Kanban",
		"budges": {
			"budges1": "Drag task between list"
		}
	},
	"Todos": "Todos",
	"Announcements": "Announcements",
	"Appointments": "Appointments",
	"Dashboard": "Dashboard",
	"Calendar": "Calendar",
	"Administration": "Administration",
	"Roles": "Roles",
	"Settings": "Settings",
	"Departments": "Departments",
	"Users": "Users",
	"Teams": "Teams",
	"Holidays": "Holidays",
	"Meetings": "Meetings",
	"Clients": "Clients",
	"Mailbox": "Mailbox",
	"File Manager": "File Manager",
	"Company Detail": "Company Detail",
	"Email Settings": "Email Settings",
	"Email Templates": "Email Templates",
	"Email Notifications": "Email Notifications",
	"Cronjob": "Cronjob",
	"Menu Allocation": "Menu Allocation",
	"Theme Settings": "Theme Settings",
	"Dashboard Settings": "Dashboard Settings",
	"System Settings": "System Settings",
	"System Update": "System Update",
	"Backup Database": "Backup Database",
	"Custom Fields": "Custom Fields",
	"Project Management": "Project Management",
	"Project Planner": "Project Planner",
	"Projects": "Projects",
	"Tasks": "Tasks",
	"Task Board": "Task Board",
	"Defects": "Defects",
	"Incidents": "Incidents",
	"Knowledge Base": "Knowledge Base",
	"Team Boards": "Team Boards",
	"Reports": "Reports",
	"Articles": "Articles",
	"Activate Account": "Activate Account",
	"Change Email": "Change Email",
	"Forgot Password": "Forgot Password",
	"Register Email": "Register Email",
	"Reset Password": "Reset Password",
	"Welcome Email": "Welcome Email",
	"Meeting": "Meeting",
	"Announcement": "Announcement",
	"Assigned Project": "Assigned Project",
	"Notification Client": "Notification Client",
	"Complete Projects": "Complete Projects",
	"Project Comments": "Project Comments",
	"Project Attachment": "Project Attachment",
	"Task Assigned": "Task Assigned",
	"Task Comments": "Task Comments",
	"Tasks Attachment": "Tasks Attachment",
	"Task Updated": "Task Updated",
	"Defect Assigned": "Defect Assigned",
	"Defect Comments": "Defect Comments",
	"Defect Attachment": "Defect Attachment",
	"Defect Updated": "Defect Updated",
	"Incident Assigned": "Incident Assigned",
	"Incident Comments": "Incident Comments",
	"Incident Attachment": "Incident Attachment",
	"Incident Updated": "Incident Updated",
	"Timesheet": "Timesheet"
}';
		$textdata = json_decode($text,true);
		foreach($textdata as $t)
		{
			if(count($t) > 0)
			{
				foreach($t as $e)
				{
					if(count($e) > 0)
					{
						foreach($e as $keys => $values)
						{
							echo $keys." : ".$values."<br>";
						}
					}
					else
					{
						var_dump($e);
					}
					
				}
			}
			else
			{
				var_dump($t);
			}
			echo "<hr>";
		}
	}
    
}