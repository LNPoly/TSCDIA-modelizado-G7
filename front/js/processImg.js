async function uploadImage() {
    const input = document.getElementById('image-input');
    const formData = new FormData();
    formData.append('image', input.files[0]);

    try {
        const response = await fetch('http://localhost:5000/process', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        console.log("Resultado Python:", data.python);
        console.log("Resultado R:", data.r);
    } catch (error) {
        console.error("Error:", error);
    }
}