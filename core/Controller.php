<?php
class Controller {
    /** Render a view inside a layout. $data keys become variables in the view. */
    protected function render(string $view, array $data = [], string $layout = 'main'): void {
        extract($data);
        ob_start();
        require ROOT . '/view/' . $view . '.php';
        $content = ob_get_clean();
        require ROOT . '/view/layout/' . $layout . '.php';
    }

    protected function redirect(string $url): void {
        header('Location: ' . $url);
        exit;
    }

    protected function json(array $data): void {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function isAdmin(): bool {
        return isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin';
    }

    protected function requireAdmin(): void {
        if (!$this->isAdmin()) {
            $this->redirect(url('/'));
        }
    }
}
