export const computedFormInputs = (module, params) => {
    if (typeof params === 'string') {
        params = [params]
    }
    const inputs = {}

    params.forEach(param => {
        inputs[param] = {
            get () {
                return this.$store.state[module][param]
            },
            set (value) {
                const methodName = 'set' + param.charAt(0).toUpperCase() + param.slice(1)
                this.$store.commit(module + '/' + methodName, value)
            }
        }
    })

    return inputs
}