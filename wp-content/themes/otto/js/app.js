$(document).on('ready', function(){
	
	new OM.Routers.Router();

	Backbone.history.start({
		root: '/',
	});
});