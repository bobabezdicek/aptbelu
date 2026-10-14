<?php

namespace App\Livewire\Admin;

use App\Models\GalleryImage;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class GalleryManager extends Component
{
    use WithFileUploads;

    // upload „suroviny“ (originál)
    public $upload;
    // dataURL z prohlížeče po ořezu
    public ?string $croppedDataUrl = null;
    public ?int $cropTargetId = null; // když ořezávám existující

    public function render()
    {
        return view('livewire.gallery-manager', [
            'photos' => GalleryImage::orderBy('sort')->get(),
        ]);
    }

    /** Uloží nově nahranou fotku (bez ořezu) – fallback, když přeskočíš crop */
    public function saveUpload()
    {
        $this->validate([
            'upload' => 'required|image|max:8192', // 8 MB
        ]);

        $path = $this->upload->store('gallery', 'public');
        $image = getimagesize($this->upload->getRealPath());
        $sort = (int) GalleryImage::max('sort') + 1;

        GalleryImage::create([
            'path'   => $path,
            'sort'   => $sort,
            'width'  => $image[0] ?? null,
            'height' => $image[1] ?? null,
        ]);

        $this->reset(['upload','croppedDataUrl','cropTargetId']);
        session()->flash('ok','Fotka nahrána.');
    }

    /** Přijme dataURL (canvas z Cropperu) a uloží jako hlavní i náhled */
    public function saveCropped()
    {
        $this->validate([
            'croppedDataUrl' => 'required|string',
        ]);

        // data:image/png;base64,....
        if (!preg_match('/^data:image\/(\w+);base64,/', $this->croppedDataUrl, $m)) {
            $this->addError('croppedDataUrl','Neplatná data.');
            return;
        }
        $ext = strtolower($m[1]);
        if (!in_array($ext, ['png','jpeg','jpg','webp'])) $ext = 'png';
        $data = base64_decode(substr($this->croppedDataUrl, strpos($this->croppedDataUrl, ',')+1));

        $filename = 'gallery/'.uniqid('img_').'.'.$ext;
        Storage::disk('public')->put($filename, $data);

        [$w,$h] = getimagesizefromstring($data) ?: [null, null];

        if ($this->cropTargetId) {
            // ořez existující fotky -> přepíšeme path
            $p = Photo::find($this->cropTargetId);
            if ($p) {
                // starý soubor můžeme smazat
                if ($p->path && Storage::disk('public')->exists($p->path)) {
                    Storage::disk('public')->delete($p->path);
                }
                $p->update(['path' => $filename, 'width'=>$w, 'height'=>$h]);
            }
        } else {
            $sort = (int) Photo::max('sort') + 1;
            Photo::create([
                'path'   => $filename,
                'sort'   => $sort,
                'width'  => $w,
                'height' => $h,
            ]);
        }

        $this->reset(['upload','croppedDataUrl','cropTargetId']);
        session()->flash('ok','Uloženo.');
    }

    public function delete(int $id)
    {
        $p = Photo::findOrFail($id);
        if ($p->path && Storage::disk('public')->exists($p->path)) {
            Storage::disk('public')->delete($p->path);
        }
        if ($p->thumb_path && Storage::disk('public')->exists($p->thumb_path)) {
            Storage::disk('public')->delete($p->thumb_path);
        }
        $p->delete();
    }

    /** Přijme pořadí z frontendu (Sortable) – pole ID v novém pořadí */
    #[On('gallery-reorder')]
    public function reorder(array $orderedIds)
    {
        foreach ($orderedIds as $index => $id) {
            Photo::whereKey($id)->update(['sort' => $index+1]);
        }
    }

    /** otevřít modal s cropperem pro existující fotku */
    public function startCrop(int $id)
    {
        $this->cropTargetId = $id;
        $this->dispatch('open-cropper', url: Photo::findOrFail($id)->url);
    }

    #[On('upload-cropped')]
    public function uploadCropped($payload): void
    {
        $this->croppedDataUrl = is_array($payload) ? ($payload['dataUrl'] ?? null) : $payload;
        $this->saveCropped();
    }
}
