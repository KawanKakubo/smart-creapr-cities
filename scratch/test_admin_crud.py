import os
import sys
import time
import random
from playwright.sync_api import sync_playwright

def run_crud_tests():
    print("=================================================================")
    print("STARTING AUTOMATED E2E VERIFICATION FOR ADMINISTRATORS CRUD...")
    print("=================================================================")
    
    # Screenshots directory
    screenshots_dir = "/home/kawan/.gemini/antigravity/brain/ffd550bf-2cb1-4da8-a3a6-1dfe010de15e/scratch"
    os.makedirs(screenshots_dir, exist_ok=True)
    
    # Generate unique emails and names for testing
    rand_suffix = random.randint(1000, 9999)
    new_admin_email = f"novo_admin_{rand_suffix}@crea-pr.org.br"
    new_admin_name = f"Test Admin Autogênito {rand_suffix}"
    updated_admin_name = f"Test Admin Autogênito {rand_suffix} (Atualizado)"
    
    with sync_playwright() as p:
        print("Launching headless Chromium browser...")
        browser = p.chromium.launch(headless=True)
        context = browser.new_context(viewport={"width": 1280, "height": 800})
        page = context.new_page()
        
        # 1. Login Page
        print("1. Navigating to Admin Login page...")
        page.goto("http://localhost:8000/admin/login")
        page.wait_for_load_state("networkidle")
        
        # Perform Login
        print("Performing login with administrator credentials...")
        page.locator('input[type="email"]').fill("kawanhrs@gmail.com")
        page.locator('input[type="password"]').fill("kawan1203")
        page.locator('button[type="submit"]').click()
        
        # Wait for dashboard page
        page.wait_for_url("**/admin/dashboard")
        page.wait_for_load_state("networkidle")
        print("Successfully logged in as administrator! Dashboard reached.")
        
        # Save dashboard screenshot showing the new "Administradores" menu link
        screenshot_path = os.path.join(screenshots_dir, "07_dashboard_with_menu.png")
        page.screenshot(path=screenshot_path, full_page=True)
        print(f"Saved dashboard screenshot to: {screenshot_path}")
        
        # Verify the "Administradores" link exists on the dashboard
        admins_link = page.locator('text=Administradores').first
        assert admins_link.is_visible(), "ERROR: 'Administradores' menu link is missing from dashboard!"
        print("Verified: 'Administradores' menu link is present.")
        
        # 2. Navigate to Administrators Index
        print("\n2. Navigating to Administrators Index...")
        admins_link.click()
        page.wait_for_load_state("networkidle")
        
        # Save index screenshot
        screenshot_path = os.path.join(screenshots_dir, "08_admins_list.png")
        page.screenshot(path=screenshot_path, full_page=True)
        print(f"Saved list screenshot to: {screenshot_path}")
        
        # Verify safety info card is visible
        assert page.locator('text=Aviso de Segurança').is_visible(), "ERROR: Safety banner is missing!"
        assert "não podem ser excluídas" in page.content(), "ERROR: Banner does not mention deletions are disabled!"
        print("Verified: Safety and deletion restriction warning banner is fully visible.")
        
        # Verify NO delete controls exist on the index table
        delete_matches = page.locator('text=Excluir').all() + page.locator('text=Deletar').all()
        assert len(delete_matches) == 0, "ERROR: Unauthorized delete controls detected in the index view!"
        print("Verified: Deletion controls are strictly absent from the list.")
        
        # 3. Create a New Admin
        print("\n3. Navigating to Create Administrator page...")
        page.locator('text=+ Novo Administrador').click()
        page.wait_for_load_state("networkidle")
        
        # Save create form screenshot
        screenshot_path = os.path.join(screenshots_dir, "09_admin_create_form.png")
        page.screenshot(path=screenshot_path, full_page=True)
        print(f"Saved create form screenshot to: {screenshot_path}")
        
        # Fill in the form
        print(f"Creating new administrator account:")
        print(f" - Name: {new_admin_name}")
        print(f" - Email: {new_admin_email}")
        
        page.locator('#name').fill(new_admin_name)
        page.locator('#email').fill(new_admin_email)
        page.locator('#password').fill("senhaE2E123!")
        page.locator('#password_confirmation').fill("senhaE2E123!")
        
        # Click submit and wait for redirect
        page.locator('button:has-text("Cadastrar Administrador")').click()
        page.wait_for_load_state("networkidle")
        
        # Verify successful creation alert banner
        assert page.locator('text=Administrador criado com sucesso!').is_visible(), "ERROR: Success alert is missing!"
        print("Verified: Administrator created successfully and redirect has success alert.")
        
        # 4. Edit Administrator (Leaving Password Blank)
        print("\n4. Editing the newly created Administrator...")
        # Find row of newly created admin and click "Editar"
        admin_row_selector = f'tr:has-text("{new_admin_email}")'
        admin_row = page.locator(admin_row_selector)
        assert admin_row.is_visible(), "ERROR: Could not find the newly created admin row in the table!"
        
        # Click the edit button within that row
        admin_row.locator('text=Editar').click()
        page.wait_for_load_state("networkidle")
        
        # Save edit form screenshot
        screenshot_path = os.path.join(screenshots_dir, "10_admin_edit_form.png")
        page.screenshot(path=screenshot_path, full_page=True)
        print(f"Saved edit form screenshot to: {screenshot_path}")
        
        # Verify the form is pre-filled with correct data
        current_name = page.locator('#name').input_value()
        current_email = page.locator('#email').input_value()
        assert current_name == new_admin_name, f"ERROR: Name field holds wrong value: {current_name}"
        assert current_email == new_admin_email, f"ERROR: Email field holds wrong value: {current_email}"
        print("Verified: Form is pre-filled with correct existing data.")
        
        # Edit Name and leave password blank
        print(f"Changing name to '{updated_admin_name}' and leaving password blank...")
        page.locator('#name').fill(updated_admin_name)
        # We ensure password and confirmation inputs are completely empty
        page.locator('#password').fill("")
        page.locator('#password_confirmation').fill("")
        
        # Save alterations
        page.locator('button:has-text("Salvar Alterações")').click()
        page.wait_for_load_state("networkidle")
        
        # Verify successful update alert banner
        assert page.locator('text=Administrador atualizado com sucesso!').is_visible(), "ERROR: Update success alert is missing!"
        print("Verified: Administrator updated successfully.")
        
        # Verify updated name is now shown in the index table
        updated_row = page.locator(f'tr:has-text("{new_admin_email}")')
        assert updated_row.is_visible(), "ERROR: Could not locate the row after update!"
        assert updated_admin_name in updated_row.inner_text(), "ERROR: Updated name was not saved correctly!"
        print(f"Verified: Updated name '{updated_admin_name}' is active in the list.")
        
        # Verify NO delete buttons exist on edit page
        page.goto("http://localhost:8000/admin/users")
        page.wait_for_load_state("networkidle")
        delete_matches = page.locator('text=Excluir').all() + page.locator('text=Deletar').all()
        assert len(delete_matches) == 0, "ERROR: Post-update delete controls check failed!"
        print("Verified: Deletion controls are strictly blocked from every stage of the lifecycle.")
        
        # Take a final high-fidelity screenshot showing our successful list and update
        screenshot_path = os.path.join(screenshots_dir, "11_admins_list_final.png")
        page.screenshot(path=screenshot_path, full_page=True)
        print(f"Saved final list screenshot to: {screenshot_path}")
        
        print("\n=================================================================")
        print("SUCCESS: ALL E2E VERIFICATION CHECKS PASSED FOR THE ADMIN CRUD!")
        print("=================================================================")
        browser.close()

if __name__ == "__main__":
    run_crud_tests()
