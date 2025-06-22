<section class="w-full">
    @include('partials.students-heading')
    {{-- replace this later in the prod --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
    <div class="self-stretch">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full">
            {{-- content start here --}}
            <div class="p-4 lg:p-8 h-full border border-solid border-zinc-200 dark:border-zinc-700 rounded-lg space-y-4">
                <div class="font-bold text-xl">Detail santri</div>
                <div class="space-y-2">
                    <div>Nama : <span>{{ $student->name }}</span></div>
                    <div>Alamat : <span>{{ ($student->address) ? $student->address : '-' }}</span></div>
                    <div>Ttl. : <span>{{ ($student->dob) ? $student->dob : '-' }}</span></div>
                    <div>Jenis Kelamin : <span>{{ $student->gender }}</span></div>
                    <div>Kamar: <span>{{ ($student->dorm ? $student->dorm->block .' - '. $student->dorm->room_number : '-') }}</span></div>
                    <div>Kelas: <span>{!! ($student->islamicClass) ? $student->islamicClass->name .' - '. $student->islamicClass->class .'<sup>'. $student->islamicClass->sub_class .'</sup>' : '-' !!}</span></div>
                    <div>Nama Ayah : <span>{{ ($student->father_name) ? $student->father_name : '-' }}</span></div>
                    <div>Nama Ibu: <span>{{ ($student->mother_name) ? $student->mother_name : '-' }}</span></div>
                    <div>Nama Wali: <span>{{ ($student->guardian_name) ? $student->guardian_name : '-' }}</span></div>
                </div>
            </div>
            <div class="p-4 lg:p-8 h-full border border-solid border-zinc-200 dark:border-zinc-700 rounded-lg space-y-4 mt-4">
                <div class="font-bold text-xl">Riwayat Izin</div>
                @if ($student)
                @php
                    $previousMonth = null;
                    $counter = 0;
                @endphp
                {{-- github like heatmap container --}}
                <div class="overflow-x-auto">
                {{-- Month labels --}}
                <div class="grid grid-flow-col grid-rows-1 gap-1 text-xs">
                    @foreach($heatmap->chunk(7) as $week)
                        @php
                            $weekStartDate = \Carbon\Carbon::parse($week->keys()->first());
                            $currentMonth = $weekStartDate->format('M');
                        @endphp
                        <div class="w-2 lg:w-4 text-center">
                            @if ($currentMonth !== $previousMonth)
                                {{ $currentMonth }}
                                @php $previousMonth = $currentMonth; @endphp
                            @endif
                        </div>
                    @endforeach
                </div>
                {{-- Heatmap --}}
                @php
                    $dates = $heatmap->keys()->values();
                    $values = $heatmap->values()->toArray();
                @endphp
                            
                <div class="grid grid-flow-col grid-rows-7 gap-1 text-xs">
                    @foreach($heatmap as $i => $status)
                        @php
                            $color = match($status) {
                                'green' => 'bg-green-500',
                                'yellow' => 'bg-yellow-400',
                                'red' => 'bg-red-400',
                                'darker_red' => 'bg-red-800',
                                default => 'bg-zinc-500',
                            };
                        
                            $prev = $values[$loop->index - 1] ?? null;
                            $next = $values[$loop->index + 1] ?? null;
                        
                            $isStart = $status !== $prev;
                            $isEnd = $status !== $next;
                        
                            $date = $dates[$loop->index];
                            $day = \Carbon\Carbon::parse($date)->format('d');
                            $showDay = $isStart || $isEnd;
                        @endphp
                
                        <div class="{{ $color }} w-2 h-2 lg:w-4 lg:h-4 text-[6px] lg:text-xs text-center rounded-[1px] lg:rounded-xs" title="{{ $date }}">
                            {{ $showDay ? $day : '' }}
                        </div>
                    @endforeach
                </div>
                </div>
                {{-- End of heatmap container --}}            
                @endif
                <div class="flex items-center justify-start gap-2">
                    <div>
                        <div class="bg-green-500 w-2 h-2 rounded-xs inline-block"></div>
                        <div class="inline-block text-xs">Ada di pondok</div>
                    </div>
                    <div>
                        <div class="bg-yellow-400 w-2 h-2 rounded-xs inline-block"></div>
                        <div class="inline-block text-xs">Izin pulang</div>
                    </div>
                    <div>
                        <div class="bg-red-500 w-2 h-2 rounded-xs inline-block"></div>
                        <div class="inline-block text-xs">Telat</div>
                    </div>
                    <div>
                        <div class="bg-red-800 w-2 h-2 rounded-xs inline-block"></div>
                        <div class="inline-block text-xs">Tanpa izin</div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2">
                    @if (auth()->user()->role->name == 'admin' || auth()->user()->role->name == 'keamanan' || auth()->user()->role->name == 'kesehatan')
                        <flux:button icon="plus" variant="primary" onclick="window.location.href='{{ route('permits.form', ['student' => $student->id]) }}'">Izin</flux:button>
                    @endif
                    @if (auth()->user()->role->name == 'admin' || auth()->user()->role->name == 'keamanan')
                        <flux:button icon="plus" variant="primary" onclick="window.location.href='{{ route('violations.form', ['student' => $student->id]) }}'">Pelanggaran</flux:button>
                    @endif
                </div>
            </div>
            @if (auth()->user()->role->name == 'admin' || auth()->user()->role->name == 'sekretaris')
            <div class="flex justify-end items-center gap-4 mt-4">
                <flux:modal.trigger name="generate_card">
                    <flux:button icon="printer" variant="filled">Kartu</flux:button>
                </flux:modal.trigger>
                <flux:button icon="pencil" variant="filled" onclick="window.location.href='{{ route('students.edit', ['student' => $student->id]) }}'">Edit</flux:button>
                <flux:modal.trigger name="drop_student">
                    <flux:button icon="no-symbol" variant="filled">Berhenti</flux:button>
                </flux:modal.trigger>
                <flux:modal.trigger name="delete_student">
                    <flux:button icon="trash" variant="danger">Hapus</flux:button>
                </flux:modal.trigger>
            </div>
            @endif
            {{-- content end here --}}
        </div>
    </div>
    <flux:modal id="generate_card_modal" name="generate_card" class="min-w-[60rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg" class="mb-2">Generate Kartu Santri</flux:heading>
                {{-- <flux:text class="mt-2">
                    <p>Data santri akan dihapus dari sistem.</p>
                    <p>Aksi ini berdamapak permanen dan tidak bisa dikembalikan.</p>
                </flux:text> --}}
                <flux:select class="mb-2" id="card_template" wire:model="card_template" :label="__('Jenis Kartu')" placeholder="Pilih jenis kartu" required>
                    @foreach (File::files(public_path('card_template')) as $file)
                        <flux:select.option value="{{ $file->getFilename() }}">{{ $file->getFilename() }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:input type="file" id="overlayImageInput" class="mb-2" label="Foto" accept="image/*"/>
                {{-- <input type="file" id="overlayImageInput" class="mt-2" accept="image/*" /> --}}
                <canvas id="canvas" class="mb-2" width="856" height="540" style="border:1px solid #ccc;"></canvas>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Tutup</flux:button>
                </flux:modal.close>
                <flux:button variant="ghost" onclick="resetCanvas()">Reset</flux:button>
                <flux:button variant="ghost" onclick="addDetails()">Selanjutnya</flux:button>
                <flux:button variant="primary" onclick="exportImage()">Download</flux:button>
            </div>
        </div>
    </flux:modal>
    <flux:modal name="delete_student" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Hapus santri?</flux:heading>
                <flux:text class="mt-2">
                    <p>Data santri akan dihapus dari sistem.</p>
                    <p>Aksi ini berdamapak permanen dan tidak bisa dikembalikan.</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Batal</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger" wire:click="deleteStudent()">Hapus</flux:button>
            </div>
        </div>
    </flux:modal>
    <flux:modal name="drop_student" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Berhenti</flux:heading>
                <flux:text class="mt-2">
                    <p>Ubah status santri menjadi berhenti?</p>
                </flux:text>
            </div>
            <flux:input wire:model="drop_date" label="Tgl. Berhenti" type="date" />
            <flux:input wire:model="drop_reason" label="Alasan" type="text" />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary" wire:click="dropStudent()">Berhenti</flux:button>
            </div>
        </div>
    </flux:modal>
    <script>

    fabric.IText.prototype.initHiddenTextarea = (function(initHiddenTextarea) {
        return function() {
            var result = initHiddenTextarea.apply(this);
            // Assuming your modal has a specific ID or class, e.g., 'my-modal-content'
            var modalContent = document.getElementById('generate_card_modal'); 
            if (modalContent) {
                modalContent.appendChild(this.hiddenTextarea);
            } else {
                // Fallback to canvas wrapper if modal content not found
                this.canvas.wrapperEl.appendChild(this.hiddenTextarea); 
            }
            return result;
        };
    })(fabric.IText.prototype.initHiddenTextarea);

const canvas = new fabric.Canvas('canvas');

const cardText = {
    name: '{{ $student->name ?? '' }}',
    nis: '{{ $student->nis ?? '-' }}',
    address: '{{ $student->address ?? '-' }}',
    dob: '{{ $student->dob ?? '-' }}'
};

let backgroundImage = null;
let textColor = '#000000';

// Upload Background Image
document.getElementById('card_template').addEventListener('change', function (e) {
    const filename = e.target.value;
    if (!filename) return;
    // Assuming card_template images are in /card_template/ and are accessible publicly
    const imageUrl = `/card_template/${filename}`;
    backgroundImage = imageUrl;
    fabric.Image.fromURL(imageUrl, function (img) {
        canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
            scaleX: canvas.width / img.width,
            scaleY: canvas.height / img.height
        });
    }, { crossOrigin: 'anonymous' });
    if (filename == 'mahrom.png') {
        textColor = '#3494a6';
    } else {
        textColor = '#057257';
    }
});

// Upload Overlay Image
document.getElementById('overlayImageInput').addEventListener('change', function (e) {
    const reader = new FileReader();
    reader.onload = function (event) {
        fabric.Image.fromURL(event.target.result, function (img) {
            img.set({
                left: 100,
                top: 100,
                scaleX: 0.2,
                scaleY: 0.2
            });
            canvas.add(img);
            canvas.sendToBack(img);
        });
    };
    reader.readAsDataURL(e.target.files[0]);
});

function addDetails() {
    addNewBackground();
}

function addNewBackground() {
    if (backgroundImage) {
        fabric.Image.fromURL(backgroundImage, function (img) {
            img.set({
                left: 0,
                top: 0,
                scaleX: canvas.width / img.width,
                scaleY: canvas.height / img.height,
                selectable: false,
                evented: false
            });
            canvas.add(img);
            addText();
        }, { crossOrigin: 'anonymous' });
    }
}

// Add Text
function addText() {
    const text1 = new fabric.IText('Nama\nNIS\nAlamat\nTetala', {
        left: 230,
        top: 200,
        fontSize: 20,
        fontFamily: 'Arial',
        fontWeight: 'bold',
        fill: textColor,
        stroke: null,
        strokeWidth: 0
    });
    canvas.add(text1);
    canvas.bringToFront(text1);
    const text2 = new fabric.IText(': ' + cardText.name + '\n: ' + cardText.nis + '\n: ' + cardText.address + '\n: ' + cardText.dob, {
        left: 300,
        top: 200,
        fontSize: 20,
        fontFamily: 'Arial',
        fontWeight: 'bold',
        fill: textColor,
        stroke: null,
        strokeWidth: 0,
        editable: true,
    });
    canvas.add(text2);
    canvas.bringToFront(text2);
    if (document.getElementById('card_template').value === 'mahrom.png') {
        addMahromText();
        addDateTextMahrom();
    } else {
        addDateText();
    }
}

function addMahromText() {
    const father = new fabric.IText('{{ $student->father_name ?? '' }}', {
        left: 123,
        top: 420,
        fontSize: 20,
        fill: textColor,
        stroke: null,
        strokeWidth: 0,
        editable: true,
        fontFamily: 'Arial',
        fontWeight: 'bold',
        textAlign: 'center',
        originX: 'center' // Set anchor to center
    });
    canvas.add(father);
    canvas.bringToFront(father);
    const mother = new fabric.IText('{{ $student->mother_name ?? '' }}', {
        left: 732,
        top: 420,
        fontSize: 20,
        fill: textColor,
        stroke: null,
        strokeWidth: 0,
        editable: true,
        fontFamily: 'Arial',
        fontWeight: 'bold',
        textAlign: 'center',
        originX: 'center' // Set anchor to center
    });
    canvas.add(mother);
    canvas.bringToFront(mother);
}

function addDateText() {
    const date = new Date();
    const formattedDate = date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    const dateText = new fabric.IText(formattedDate, {
        left: 660,
        top: 378,
        fontSize: 17,
        fontFamily: 'Arial',
        fontWeight: 'bold',
        fill: textColor,
        stroke: null,
        strokeWidth: 0
    });
    canvas.add(dateText);
    canvas.bringToFront(dateText);
}

function addDateTextMahrom() {
    const date = new Date();
    const formattedDate = date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
    const dateText = new fabric.IText(formattedDate, {
        left: 400,
        top: 372,
        fontSize: 17,
        fontFamily: 'Arial',
        fontWeight: 'bold',
        fill: textColor,
        stroke: null,
        strokeWidth: 0
    });
    canvas.add(dateText);
    canvas.bringToFront(dateText);
}

// Export and Download Image
function exportImage() {
    canvas.discardActiveObject();
    canvas.renderAll();
    const dataURL = canvas.toDataURL({
        format: 'png',
        quality: 1.0
    });

    const link = document.createElement('a');
    link.href = dataURL;
    link.download = 'kartu-santri.png';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Helper to send base64 image as file
function dataURItoFormData(dataURI) {
    const byteString = atob(dataURI.split(',')[1]);
    const mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
    const ab = new ArrayBuffer(byteString.length);
    const ia = new Uint8Array(ab);

    for (let i = 0; i < byteString.length; i++)
        ia[i] = byteString.charCodeAt(i);

    const blob = new Blob([ab], { type: mimeString });
    const formData = new FormData();
    formData.append('image', blob, 'canvas.png');
    return formData;
}

function resetCanvas() {
    canvas.clear();
    if (backgroundImage) {
        fabric.Image.fromURL(backgroundImage, function (img) {
            canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
                scaleX: canvas.width / img.width,
                scaleY: canvas.height / img.height
            });
        }, { crossOrigin: 'anonymous' });
    }
}
</script>
</section>