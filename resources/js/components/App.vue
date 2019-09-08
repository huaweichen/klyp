<template>
    <div class="container">
        <h1>Search Movie</h1>
        <form @submit.prevent="submitForm()">
            <div class="form-group">
                <label for="searchTerm">Movie Name: </label>
                <input type="text" class="form-control" id="searchTerm"
                       placeholder="Search ..." v-model="searchTerm">
                <p class="text-danger" v-text="errorMessage()"></p>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <hr/>
        <div v-if="typeof result.Error !== 'undefined'">{{ result.Error }}</div>
        <div v-if="typeof result.title !== 'undefined'">
            <h3>Search result found:</h3>
            <h4>Title:</h4>
            <p v-html="$options.filters.highlight(result.title, searchTerm)"></p>
            <h4>Year: </h4>
            <p v-html="$options.filters.highlight(result.year, searchTerm)"></p>
            <h4>Runtime: </h4>
            <p v-html="$options.filters.highlight(result.runtime, searchTerm)"></p>
        </div>
    </div>
</template>

<script>
    export default {
        name: "App",
        data: function () {
            return {
                searchTerm: '',
                result: {},
                error: ''
            };
        },
        computed: {
            hasError: function () {
                return this.error !== '';
            }
        },
        filters: {
            highlight: (text, term) => {
                if (!text) return '';
                text = text.toString();
                return text.replace(new RegExp(term, 'ig'), '<span class=\'highlight\'>' + term + '</span>');
            }
        },
        methods: {
            errorMessage: function () {
                if (this.hasError) {
                    return this.error;
                }
            },
            submitForm: function () {
                axios.post('/api/search', {searchTerm: this.searchTerm})
                    .then(response => {
                        this.error = '';
                        this.result = response.data;
                    })
                    .catch(errors => {
                        this.error = errors.response.data.message;
                    });
            }
        }
    }
</script>

<style>
    span.highlight {
        background-color: green;
        color: white;
    }
</style>
