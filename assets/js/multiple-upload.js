export function multipleUpload()
{
    document
        .querySelectorAll('.add')
        .forEach(btn => {
            btn.addEventListener("click", addFormToCollection)
        });
}

let fileInput = document.querySelector('.file-input');
/** File[] fileList */
let fileList = [];

if(fileInput)
{

    fileInput.addEventListener('change', function (event) {
        const collectionHolder = document.querySelector('.files');
            fileList = [];
            for (let i = 0; i < fileInput.files.length; i++) {
                const item = document.createElement('li');
                item.classList.add('li-file-input')
                item.innerHTML = collectionHolder
                    .dataset
                    .prototype
                    .replace(
                        /__name__/g,
                        collectionHolder.dataset.index
                    );
                collectionHolder.appendChild(item);
                let input = item.querySelector("input[type='file']")
                let dataTransfer = new DataTransfer();
                dataTransfer.items.add(fileInput.files[i])
                input.files = dataTransfer.files
                collectionHolder.dataset.index++;
            }
        }
    )
}