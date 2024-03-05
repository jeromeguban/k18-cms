// Require in a base component context
const requireComponent = require.context('./defaults', true, /[\w-]+\.vue$/)

requireComponent.keys().forEach(fileName => {
	// Get component config
	const componentConfig = requireComponent(fileName)
	// console.log(componentConfig)
	// Get PascalCase name of component

	const componentName = _.upperFirst(
		_.camelCase((componentConfig.default.name || fileName).replace(/^\.\//, '').replace(/\.\w+$/, ''))
	)
	
	Vue.component(componentName, componentConfig.default || componentConfig)
})