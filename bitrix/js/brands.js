document.addEventListener("DOMContentLoaded", function() {
  document.querySelectorAll('.brands-track').forEach(track => {
    const originalItems = track.innerHTML;
    // Убираем старые дубли (если были)
    track.innerHTML = originalItems;
    // Дублируем содержимое минимум 4 раза, чтобы гарантированно перекрыть экран
    let clones = originalItems;
    for (let i = 0; i < 4; i++) {
      clones += originalItems;
    }
    track.innerHTML = clones;
    // Сбрасываем анимацию, чтобы она работала плавно
    track.style.animation = 'none';
    track.offsetHeight; // форсируем перерисовку
    track.style.animation = '';
  });
});