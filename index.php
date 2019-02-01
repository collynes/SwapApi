<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SWAPI</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="materialize.min.css">
    <script src="vue.js"></script>
    <script src="axios.min.js"></script>
</head>

<body>
    <?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    ?>
    <div id="swapi">
        <component :is="currentComponent" :characters="characters" :character_uri="character_uri"></component>
    </div>
</body>
<script>
    Vue.component('list-characters', {
        props: ['characters'],
        methods: {
            showDetail(character_uri) {
                this.$root.$emit('show-detail', {
                    uri: character_uri
                });
            }

        },
        template: `
        <div class="container">
            <h3>List Of Characters</h3>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Birth Year</th>
                                     
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="person in characters" @click="showDetail(person.url)">
                        <td>{{person.name}}</td>
                        <td>{{person.gender}}</td>
                        <td>{{person.birth_year}}</td>
                        
                    </tr>
                </tbody>
            </table>
            <pagination></pagination>
        </div>
    `
    });
    Vue.component('saveToLocalStorage', {
        data() {
            return {
                character: []
            }
        },
        methods: {
            save(person) {
                // let itemsArray = localStorage.getItem('items') ? JSON.parse(localStorage.getItem('items')) : [];
                // itemsArray.push(input.value);
                // localStorage.setItem('items', JSON.stringify(itemsArray));
                // echo 'You Liked This';
            },
            clear() {
                localStorage.clear();
            },
            get() {
                let itemsArray = localStorage.getItem('items') ? JSON.parse(localStorage.getItem('items')) :
                    [];

            },
        }
    });

    Vue.component('list-detail-character', {
        props: ['character_uri'],
        data() {
            return {
                character: [],
                remFevs: [],
                favs:[],
                _items:[]
            }
        },

        mounted() {
            this.loadDetail();
            this.loadFav();
        },
        methods: {
            loadDetail() {
                axios.get(this.character_uri).then((person) => {
                    // console.log(person);
                    this.character = person.data;
                }).catch(err => {
                    console.log(err);
                })
            },
            loadFav() {
                var itemsArray = localStorage.getItem('items') ? JSON.parse(localStorage.getItem('items')) : JSON.stringify([]);
          //      localStorage.setItem('items', JSON.stringify([]));
                // const data = JSON.parse(localStorage);
                // for (i = 0; i <= localStorage.length - 1; i++) {
                //     key = localStorage.key(i);
                //     console.log(localStorage.getItem(key));
                //     this.pevs = localStorage.getItem(key);

                // }
                this._items = localStorage;


            },
            removeFav(person) {
                console.log(person);
                 for (remove in person) {
                     person.splice(14, 2);
                 }
                  console.log('@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@2');
                  console.log(person);
                // localStorage.clear(person[person.name]);

            },
            save(person) {

                // var itemsArray = [];
                // this.itemsArray.push("person");
                // localStorage.setItem('items', JSON.stringify(itemsArray));
                // console.log(person);
                 var counter;
                // var record = JSON.stringify(person);
               

                if (localStorage.getItem(person.name) === null) {
                    localStorage.setItem(person.name, JSON.stringify(person));
                    this.counter++;
                } else {
                    console.log('Already In Favorites');
                }


            },

        },
        template: `
       
            <div class="container">
                    <div class="row">
                        <div class="col s12 m12">
                           
                            <div class="card blue-grey darken-1">
                                    <div class="card-content white-text">
                                        <span class="card-title">More Details of {{character.name}}</span>
                                            <ul>
                                                <li>Name : {{character.name}}</li>
                                                <li>Gender : {{character.gender}}</li>
                                                <li>Mass :  {{character.mass}}</li>
                                                <li>Height : {{character.mass}}</li>
                                                <li>Birli Year : {{character.height}}</li>
                                                <li>Eye color:  {{character.eye_color}}</li>
                                                <li>Hair Color :  {{character.hair_color}}</li>
                                                <li>Skin Color :  {{character.skin_color}}</li>
                                            
                                            </ul>
                                   </div>
                                </div>
                                    <div class="card-action">
                                       <img src="love.png" margin left="50%" width="50" height="50" @click="save(character)"/>
                                    </div>
                            </div>
                    </div>
                           
            <div class="row">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Mass</th>
                                <th>Height</th>
                                <th>Birth Year</th>
                                <th>Eye color</th>
                                <th>Hair Color</th>
                                <th>Skin Color</th>
                                <th>Delete Favorite</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="it in favs">
                                <td>{{it.name}}</td>
                                <td>{{it.gender}}</td>
                                <td>{{it.mass}}</td>
                                <td>{{it.mass}}</td>
                                <td>{{it.height}}</td>
                                <td>{{it.eye_color}}</td>
                                <td>{{it.hair_color}}</td>
                                <td>{{it.skin_color}}</td>
                                <td> 
                                 <img src="love.png" margin left="50%" width="50" height="50" @click="removeFav(favs)"/>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
         </div>`
    });
    Vue.component('pagination', {
        data() {
            return {
                pages: 1,
                count: 0,
                next: ''
            }
        },
        mounted() {
            this.$root.$on('updatePagination', (data) => {
                console.log(data);
                this.count = data.count;
                this.next = data.next;
                this.paginate();
            });
        },
        methods: {
            paginate() {
                this.pages = Math.ceil(this.count / 10);
                //  console.log('fish:'+ this.count);
            },
            makeArray(c) {
                console.log(c);
                var pageArray = [];
                for (var i = 1; i <= c; i++) {
                    pageArray.push(i);
                }
                console.log(pageArray);
                return pageArray;
            }
        },
        template: `
        <div>
        <ul class="pagination">
            <li class="disabled"><a href="#!"><i class="material-icons">chevron_left</i></a></li>
            <li class="active"><a href="#!">1</a></li>
            <li class="waves-effect" v-for="page in makeArray(pages)"><a href="#!">page</a></li>
            <li class="waves-effect"><a href="#!"><i class="material-icons">chevron_right</i></a></li>
        </ul>
        

        </div>
        `
    });
    var app = new Vue({
        el: '#swapi',
        data: {
            message: 'Hello Vue!',
            characters: [],
            swapiRootURI: 'https://swapi.co/api/',
            instance: '',
            currentComponent: 'list-characters',
            character_uri: ''
        },
        mounted() {
            this.$root.$on('show-detail', (data) => {
                console.log(data);
                this.character_uri = data.uri;
                this.currentComponent = 'list-detail-character';

            });
            this.instance = axios.create({
                baseURL: this.swapiRootURI
            });
            this.loadSwapi();
        },
        methods: {
            loadSwapi() {
                this.instance.get('people').then((people) => {
                    console.log(people);
                    this.characters = people.data.results;
                    this.$root.$emit('updatePagination', {
                        next: this.characters.next,
                        count: this.characters.count
                    });
                }).catch(err => {
                    console.log(err);
                })
            }
        }
    })
</script>

</html>