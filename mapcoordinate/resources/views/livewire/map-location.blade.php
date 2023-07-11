<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    MapBox
                </div>
                <div class="card-body">

                    <div wire:ignore id='map' style='width: 100%; height: 80vh;'></div>
                </div>
            </div>

        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    Form
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <lable>Longtitude</lable>
                            <input wire:model="long" type="text" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <lable>Lattitude</lable>
                            <input wire:model="lat" type="text" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="card-body">

                </div>
            </div>

        </div>
    </div>


@push('scripts')
    <script>


        document.addEventListener('livewire:load',()=>{

            const defaultLocation = [110.39976129799044, -7.0004851793118235]

            mapboxgl.accessToken = '{{env("MAPBOX_KEY")}}';
        var map = new mapboxgl.Map({
            container: 'map',
            center: defaultLocation,
            zoom: 11.15,
        });

        //Poligon Location (not direct)
        map.on('load', () => map.addSource('maine', {
            'type' : 'geoJson',
            'data' : {
                'type' : 'Feature',
                'geometry': {
                    'type' : 'Polygon',
                    'coordinate' : [
                        [
                            [-67.13734, 45.13745],
                            [-66.96466, 44.8097],
                            [-68.03252, 44.3252],
                            [-69.06, 43.98],
                            [-70.11617, 43.68405],
                            [-70.64573, 43.09008],
                            [-70.75102, 43.08003],
                            [-70.79761, 43.21973],
                            [-70.98176, 43.36789],
                            [-70.94416, 43.46633],
                            [-71.08482, 45.30524],
                            [-70.66002, 45.46022],
                            [-70.30495, 45.91479],
                            [-70.00014, 46.69317],
                            [-69.23708, 47.44777],
                            [-68.90478, 47.18479],
                            [-68.2343, 47.35462],
                            [-67.79035, 47.06624],
                            [-67.79141, 45.70258],
                            [-67.13734, 45.13745]
                        ]
                    ]
                }
            }
        }))

            map.addLayer({
                'id': 'maine',
                'type': 'fill',
                'source': 'maine', // reference the data source
                'layout': {},
                'paint': {
                    'fill-color': '#0080ff', // blue color fill
                    'fill-opacity': 0.5
                }
            });
// Add a black outline around the polygon.
            map.addLayer({
                'id': 'outline',
                'type': 'line',
                'source': 'maine',
                'layout': {},
                'paint': {
                    'line-color': '#000',
                    'line-width': 3
                }
            });


        //Single Mark Location (direct from MapLocation)
        const loadLocations =(geoJson) => {
            geoJson.features.forEach((location) =>{
                const {geometry, properties} = location
                const {iconSize, locationId, title, image, description} = properties

                let  markerElement = document.createElement('div')
                markerElement.className = 'marker' + locationId
                markerElement.id = locationId
                markerElement.style.backgroundImage = 'url(https://static.thenounproject.com/png/2648360-200.png)'
                markerElement.style.backgroundSize ='cover'
                markerElement.style.width ='25px'
                markerElement.style.height ='25px'

                const content =`
                    <div style="overflow-y, auto;max-height: 400px width:100%">
                    <table class="table table-sm mt-2">
                        <tbody>
                        <tr>
                            <td>Title</td>
                            <td>${title}</td>
                        </tr>
                        <tr>
                            <td>Picture</td>
                            <td><img style="max-height: 300px" src="${image}"loading="lazy" class="img-fluid ">

                        </tr>
<tr>
                            <td>Description</td>
                            <td>${description}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                `

                const popUp = new mapboxgl.Popup({
                    offset:25
                }).setHTML(content).setMaxWidth("200px")

                new mapboxgl.Marker(markerElement)
                    .setLngLat(geometry.coordinates)
                    .setPopup(popUp)
                .addTo(map)
            })
        }
        loadLocations({!! $geoJson !!});

        const style = "outdoors-v11"
        map.setStyle(`mapbox://styles/mapbox/${style}`)
        map.addControl(new mapboxgl.NavigationControl())
            map.on('click', (e) => {
                const longtitude = e.lngLat.lng
                const lattitude = e.lngLat.lat

                @this.long = longtitude
                @this.lat = lattitude
            } );


        })
    </script>
@endpush
</div>
