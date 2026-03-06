<?php

namespace Database\Seeders;

use App\Enums\ApplicationStatus;
use App\Enums\CommunicationType;
use App\Enums\CpdActivitySource;
use App\Enums\EventStatus;
use App\Enums\EventType;
use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\MembershipStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PostStatus;
use App\Enums\RegistrationStatus;
use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Enums\TicketType;
use App\Models\Branch;
use App\Models\Budget;
use App\Models\BudgetLine;
use App\Models\Committee;
use App\Models\Communication;
use App\Models\CommunicationRecipient;
use App\Models\CostCenter;
use App\Models\CpdActivity;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\EventTicketType;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Membership;
use App\Models\MembershipApplication;
use App\Models\MembershipTier;
use App\Models\NumberSequence;
use App\Models\Payment;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\Receipt;
use App\Models\Ticket;
use App\Models\TicketResponse;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $admin = User::where('email', 'admin@psk.or.ke')->first();

            // -------------------------------------------------------
            // 1. Admin Users
            // -------------------------------------------------------
            $financeAdmin = User::create([
                'first_name' => 'Grace',
                'last_name' => 'Wanjiku',
                'email' => 'finance@psk.or.ke',
                'phone' => '+254700100100',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'is_active' => true,
                'created_at' => '2026-01-02 08:00:00',
            ]);
            $financeAdmin->assignRole('Finance');

            $secretaryAdmin = User::create([
                'first_name' => 'James',
                'last_name' => 'Ochieng',
                'email' => 'secretary@psk.or.ke',
                'phone' => '+254700200200',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'is_active' => true,
                'created_at' => '2026-01-02 08:00:00',
            ]);
            $secretaryAdmin->assignRole('Admin');

            // -------------------------------------------------------
            // 2. Member Users (20)
            // -------------------------------------------------------
            $memberData = [
                ['first_name' => 'Wanjiku', 'last_name' => 'Kamau', 'gender' => 'female', 'dob' => '1990-03-15', 'ppb' => 'PPB/10234'],
                ['first_name' => 'Peter', 'last_name' => 'Ochieng', 'gender' => 'male', 'dob' => '1988-07-22', 'ppb' => 'PPB/10456'],
                ['first_name' => 'Muthoni', 'last_name' => 'Njoroge', 'gender' => 'female', 'dob' => '1992-11-08', 'ppb' => 'PPB/10789'],
                ['first_name' => 'David', 'last_name' => 'Mwangi', 'gender' => 'male', 'dob' => '1985-05-30', 'ppb' => 'PPB/11023'],
                ['first_name' => 'Atieno', 'last_name' => 'Oduor', 'gender' => 'female', 'dob' => '1991-09-12', 'ppb' => 'PPB/11245'],
                ['first_name' => 'John', 'last_name' => 'Kipchoge', 'gender' => 'male', 'dob' => '1987-01-25', 'ppb' => 'PPB/11467'],
                ['first_name' => 'Akinyi', 'last_name' => 'Otieno', 'gender' => 'female', 'dob' => '1993-04-18', 'ppb' => 'PPB/11689'],
                ['first_name' => 'Samuel', 'last_name' => 'Kimani', 'gender' => 'male', 'dob' => '1986-08-07', 'ppb' => 'PPB/11890'],
                ['first_name' => 'Chebet', 'last_name' => 'Kiplagat', 'gender' => 'female', 'dob' => '1994-12-03', 'ppb' => 'PPB/12012'],
                ['first_name' => 'Joseph', 'last_name' => 'Kariuki', 'gender' => 'male', 'dob' => '1989-06-20', 'ppb' => 'PPB/12234'],
                ['first_name' => 'Wambui', 'last_name' => 'Ngugi', 'gender' => 'female', 'dob' => '1995-02-14', 'ppb' => 'PPB/12456'],
                ['first_name' => 'Michael', 'last_name' => 'Mutua', 'gender' => 'male', 'dob' => '1984-10-28', 'ppb' => 'PPB/12678'],
                ['first_name' => 'Nyambura', 'last_name' => 'Wainaina', 'gender' => 'female', 'dob' => '1996-07-11', 'ppb' => 'PPB/12890'],
                ['first_name' => 'Robert', 'last_name' => 'Oduor', 'gender' => 'male', 'dob' => '1983-03-05', 'ppb' => 'PPB/13012'],
                ['first_name' => 'Wairimu', 'last_name' => 'Maina', 'gender' => 'female', 'dob' => '1997-08-19', 'ppb' => 'PPB/13234'],
                ['first_name' => 'Daniel', 'last_name' => 'Njoroge', 'gender' => 'male', 'dob' => '1990-12-01', 'ppb' => 'PPB/13456'],
                ['first_name' => 'Auma', 'last_name' => 'Owino', 'gender' => 'female', 'dob' => '1998-05-23', 'ppb' => 'PPB/13678'],
                ['first_name' => 'George', 'last_name' => 'Kamau', 'gender' => 'male', 'dob' => '2001-09-15', 'ppb' => null],
                ['first_name' => 'Cherop', 'last_name' => 'Koech', 'gender' => 'female', 'dob' => '2000-11-30', 'ppb' => null],
                ['first_name' => 'Brian', 'last_name' => 'Kiprotich', 'gender' => 'male', 'dob' => '2002-04-08', 'ppb' => null],
            ];

            $members = [];
            $phoneCounter = 710000000;
            foreach ($memberData as $index => $data) {
                $createdAt = '2026-01-' . str_pad(($index % 28) + 1, 2, '0', STR_PAD_LEFT) . ' 09:00:00';
                $email = strtolower($data['first_name'] . '.' . $data['last_name']) . '@email.com';

                $user = User::create([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $email,
                    'phone' => '+254' . $phoneCounter++,
                    'ppb_registration_no' => $data['ppb'],
                    'date_of_birth' => $data['dob'],
                    'gender' => $data['gender'],
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                    'is_active' => true,
                    'created_at' => $createdAt,
                ]);
                $user->assignRole('Member');
                $members[] = $user;
            }

            // -------------------------------------------------------
            // 3. Memberships (15 active, 3 expired, 2 pending)
            // -------------------------------------------------------
            $tiers = MembershipTier::all()->keyBy('name');
            $membershipSeq = NumberSequence::where('type', 'membership_number')->first();

            // Tier distribution: Student(3), Intern(3), Pharmacist(8), Fellow(3), Corporate(2), Honorary(1)
            $tierAssignments = [
                'Pharmacist', 'Pharmacist', 'Pharmacist', 'Pharmacist',   // 0-3
                'Fellow', 'Pharmacist', 'Fellow', 'Pharmacist',           // 4-7
                'Intern', 'Pharmacist', 'Intern', 'Fellow',               // 8-11
                'Corporate', 'Pharmacist', 'Corporate', 'Honorary',       // 12-15
                'Intern', 'Student', 'Student', 'Student',                // 16-19
            ];

            foreach ($members as $index => $member) {
                $tierName = $tierAssignments[$index];
                $tier = $tiers[$tierName];

                if ($index < 15) {
                    // Active memberships
                    $membershipNumber = $membershipSeq->generateNext();
                    Membership::create([
                        'user_id' => $member->id,
                        'membership_tier_id' => $tier->id,
                        'membership_number' => $membershipNumber,
                        'status' => MembershipStatus::Active,
                        'application_date' => '2026-01-' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                        'approval_date' => '2026-01-' . str_pad($index + 5, 2, '0', STR_PAD_LEFT),
                        'approved_by' => $admin->id,
                        'expiry_date' => '2026-12-31',
                        'created_at' => '2026-01-' . str_pad($index + 1, 2, '0', STR_PAD_LEFT) . ' 10:00:00',
                    ]);
                } elseif ($index < 18) {
                    // Expired memberships
                    $membershipNumber = $membershipSeq->generateNext();
                    Membership::create([
                        'user_id' => $member->id,
                        'membership_tier_id' => $tier->id,
                        'membership_number' => $membershipNumber,
                        'status' => MembershipStatus::Expired,
                        'application_date' => '2025-01-15',
                        'approval_date' => '2025-01-20',
                        'approved_by' => $admin->id,
                        'expiry_date' => '2025-12-31',
                        'created_at' => '2025-01-15 10:00:00',
                    ]);
                } else {
                    // Pending memberships
                    Membership::create([
                        'user_id' => $member->id,
                        'membership_tier_id' => $tier->id,
                        'membership_number' => $membershipSeq->generateNext(),
                        'status' => MembershipStatus::Pending,
                        'application_date' => '2026-02-15',
                        'created_at' => '2026-02-15 10:00:00',
                    ]);
                }
            }

            // -------------------------------------------------------
            // 4. Membership Applications (5)
            // -------------------------------------------------------
            // 3 Submitted (pending review)
            foreach ([0, 1, 2] as $i) {
                MembershipApplication::create([
                    'user_id' => $members[17 + $i >= 20 ? 17 : 17 + $i]->id,
                    'membership_tier_id' => $tiers[['Student', 'Student', 'Intern'][$i]]->id,
                    'status' => ApplicationStatus::Submitted,
                    'submitted_at' => '2026-02-' . str_pad(20 + $i, 2, '0', STR_PAD_LEFT) . ' 14:00:00',
                    'form_data' => json_encode([
                        'institution' => ['University of Nairobi', 'Kenyatta University', 'Moi University'][$i],
                        'year_of_study' => [3, 4, 'Intern'][$i],
                    ]),
                    'created_at' => '2026-02-' . str_pad(20 + $i, 2, '0', STR_PAD_LEFT) . ' 14:00:00',
                ]);
            }

            // 1 Approved
            MembershipApplication::create([
                'user_id' => $members[0]->id,
                'membership_tier_id' => $tiers['Pharmacist']->id,
                'status' => ApplicationStatus::Approved,
                'submitted_at' => '2026-01-05 10:00:00',
                'reviewed_by' => $admin->id,
                'reviewed_at' => '2026-01-07 14:00:00',
                'review_notes' => 'All documents verified. PPB registration confirmed.',
                'form_data' => json_encode([
                    'institution' => 'University of Nairobi',
                    'graduation_year' => 2014,
                ]),
                'created_at' => '2026-01-05 10:00:00',
            ]);

            // 1 Rejected
            MembershipApplication::create([
                'user_id' => $members[15]->id,
                'membership_tier_id' => $tiers['Fellow']->id,
                'status' => ApplicationStatus::Rejected,
                'submitted_at' => '2026-01-10 09:00:00',
                'reviewed_by' => $admin->id,
                'reviewed_at' => '2026-01-12 16:00:00',
                'review_notes' => 'Does not meet the minimum 10 years of practice requirement for Fellow tier.',
                'rejection_reason' => 'Insufficient years of practice. Fellow tier requires a minimum of 10 years of registered pharmacy practice and documented contributions to the profession.',
                'form_data' => json_encode([
                    'institution' => 'University of Nairobi',
                    'graduation_year' => 2020,
                    'years_of_practice' => 5,
                ]),
                'created_at' => '2026-01-10 09:00:00',
            ]);

            // -------------------------------------------------------
            // 5. Cost Centers (create before events and invoices)
            // -------------------------------------------------------
            $ccHQ = CostCenter::create([
                'name' => 'Headquarters Operations',
                'code' => 'CC-HQ-001',
                'type' => 'department',
                'description' => 'PSK headquarters operational expenses including staff, rent, utilities, and administration.',
                'is_active' => true,
                'created_at' => '2026-01-01 08:00:00',
            ]);

            $ccConf = CostCenter::create([
                'name' => 'Annual Conference 2026',
                'code' => 'CC-CONF-2026',
                'type' => 'project',
                'description' => 'Budget and expenses for the PSK Annual Scientific Conference 2026.',
                'is_active' => true,
                'created_at' => '2026-01-15 08:00:00',
            ]);

            $ccBranch = CostCenter::create([
                'name' => 'Branch Activities',
                'code' => 'CC-BR-001',
                'type' => 'department',
                'description' => 'Consolidated cost center for all branch-level activities and expenses.',
                'is_active' => true,
                'created_at' => '2026-01-01 08:00:00',
            ]);

            // -------------------------------------------------------
            // 6. Events (6)
            // -------------------------------------------------------
            $event1 = Event::create([
                'title' => 'PSK Annual Scientific Conference 2026',
                'description' => 'The flagship annual event bringing together pharmacists from across Kenya for three days of scientific presentations, workshops, and networking. Theme: "Advancing Pharmaceutical Care in the Digital Age".',
                'short_description' => 'Three-day conference featuring scientific presentations, workshops, and networking opportunities for pharmacy professionals.',
                'type' => EventType::Conference,
                'status' => EventStatus::Published,
                'venue_name' => 'Kenyatta International Convention Centre',
                'venue_address' => 'Harambee Avenue, Nairobi, Kenya',
                'is_virtual' => false,
                'start_date' => '2026-07-15 08:00:00',
                'end_date' => '2026-07-17 17:00:00',
                'registration_opens' => '2026-03-01 00:00:00',
                'registration_closes' => '2026-07-10 23:59:59',
                'max_attendees' => 500,
                'cost_center_id' => $ccConf->id,
                'cpd_points' => 15,
                'created_by' => $admin->id,
                'published_at' => '2026-02-28 10:00:00',
                'created_at' => '2026-01-20 10:00:00',
            ]);

            $ticket1a = EventTicketType::create([
                'event_id' => $event1->id,
                'name' => 'Early Bird',
                'description' => 'Discounted rate for early registrations. Includes all sessions, materials, and meals.',
                'price' => 5000.00,
                'quantity' => 200,
                'sold_count' => 0,
                'sale_starts' => '2026-03-01 00:00:00',
                'sale_ends' => '2026-05-31 23:59:59',
                'is_active' => true,
                'sort_order' => 1,
            ]);

            $ticket1b = EventTicketType::create([
                'event_id' => $event1->id,
                'name' => 'Regular',
                'description' => 'Standard registration rate. Includes all sessions, materials, and meals.',
                'price' => 7500.00,
                'quantity' => 300,
                'sold_count' => 0,
                'sale_starts' => '2026-06-01 00:00:00',
                'sale_ends' => '2026-07-10 23:59:59',
                'is_active' => true,
                'sort_order' => 2,
            ]);

            $event2 = Event::create([
                'title' => 'Pharmacy Practice Workshop',
                'description' => 'A virtual interactive workshop covering current best practices in community and hospital pharmacy. Featuring case studies and group discussions.',
                'short_description' => 'Virtual workshop on pharmacy best practices with interactive case studies.',
                'type' => EventType::Workshop,
                'status' => EventStatus::RegistrationOpen,
                'venue_name' => null,
                'venue_address' => null,
                'is_virtual' => true,
                'virtual_link' => 'https://zoom.us/j/psk-workshop-2026',
                'start_date' => '2026-04-20 09:00:00',
                'end_date' => '2026-04-20 16:00:00',
                'registration_opens' => '2026-03-01 00:00:00',
                'registration_closes' => '2026-04-18 23:59:59',
                'max_attendees' => null,
                'cpd_points' => 8,
                'created_by' => $admin->id,
                'published_at' => '2026-02-25 10:00:00',
                'created_at' => '2026-02-15 10:00:00',
            ]);

            $ticket2 = EventTicketType::create([
                'event_id' => $event2->id,
                'name' => 'Free Registration',
                'description' => 'Free virtual attendance for all PSK members.',
                'price' => 0.00,
                'quantity' => null,
                'sold_count' => 0,
                'is_active' => true,
                'sort_order' => 1,
            ]);

            $event3 = Event::create([
                'title' => 'Drug Interaction Masterclass',
                'description' => 'An advanced masterclass on clinically significant drug interactions, pharmacokinetics, and patient safety. Led by Prof. Amina Hassan, Clinical Pharmacologist.',
                'short_description' => 'Advanced masterclass on drug interactions and patient safety.',
                'type' => EventType::Seminar,
                'status' => EventStatus::Published,
                'venue_name' => 'Nairobi Hospital',
                'venue_address' => 'Argwings Kodhek Road, Nairobi',
                'is_virtual' => false,
                'start_date' => '2026-05-10 09:00:00',
                'end_date' => '2026-05-10 16:00:00',
                'registration_opens' => '2026-03-15 00:00:00',
                'registration_closes' => '2026-05-08 23:59:59',
                'max_attendees' => 100,
                'cpd_points' => 5,
                'created_by' => $secretaryAdmin->id,
                'published_at' => '2026-03-01 10:00:00',
                'created_at' => '2026-02-20 10:00:00',
            ]);

            $ticket3 = EventTicketType::create([
                'event_id' => $event3->id,
                'name' => 'General Admission',
                'description' => 'Includes masterclass session, course materials, and lunch.',
                'price' => 2000.00,
                'quantity' => 100,
                'sold_count' => 0,
                'is_active' => true,
                'sort_order' => 1,
            ]);

            $event4 = Event::create([
                'title' => 'Regulatory Updates Forum',
                'description' => 'A half-day forum on recent regulatory changes from the Pharmacy and Poisons Board, Kenya Revenue Authority eTIMS requirements, and NHIF/SHA pharmaceutical guidelines.',
                'short_description' => 'Forum covering PPB, KRA, and NHIF regulatory updates for pharmacists.',
                'type' => EventType::Seminar,
                'status' => EventStatus::Published,
                'venue_name' => 'Hilton Nairobi',
                'venue_address' => 'Mama Ngina Street, Nairobi',
                'is_virtual' => false,
                'start_date' => '2026-06-05 09:00:00',
                'end_date' => '2026-06-05 13:00:00',
                'registration_opens' => '2026-04-01 00:00:00',
                'registration_closes' => '2026-06-03 23:59:59',
                'max_attendees' => 150,
                'cpd_points' => 4,
                'created_by' => $admin->id,
                'published_at' => '2026-03-05 10:00:00',
                'created_at' => '2026-02-25 10:00:00',
            ]);

            $ticket4 = EventTicketType::create([
                'event_id' => $event4->id,
                'name' => 'General Admission',
                'description' => 'Includes forum session, regulatory briefing pack, and refreshments.',
                'price' => 1500.00,
                'quantity' => 150,
                'sold_count' => 0,
                'is_active' => true,
                'sort_order' => 1,
            ]);

            $event5 = Event::create([
                'title' => 'Community Pharmacy Summit',
                'description' => 'A two-day summit focused on the evolving role of community pharmacists in Kenya\'s healthcare system. Topics include pharmaceutical care services, business management, and public health integration.',
                'short_description' => 'Summit exploring the expanding role of community pharmacy in Kenya.',
                'type' => EventType::Conference,
                'status' => EventStatus::Completed,
                'venue_name' => 'Kenyatta International Convention Centre',
                'venue_address' => 'Harambee Avenue, Nairobi, Kenya',
                'is_virtual' => false,
                'start_date' => '2026-02-18 08:00:00',
                'end_date' => '2026-02-19 17:00:00',
                'registration_opens' => '2025-12-01 00:00:00',
                'registration_closes' => '2026-02-15 23:59:59',
                'max_attendees' => 300,
                'cpd_points' => 10,
                'created_by' => $admin->id,
                'published_at' => '2025-12-01 10:00:00',
                'created_at' => '2025-11-15 10:00:00',
            ]);

            $ticket5 = EventTicketType::create([
                'event_id' => $event5->id,
                'name' => 'Standard',
                'description' => 'Two-day pass including all sessions, meals, and summit materials.',
                'price' => 3000.00,
                'quantity' => 300,
                'sold_count' => 45,
                'is_active' => false,
                'sort_order' => 1,
            ]);

            $event6 = Event::create([
                'title' => 'Student Pharmacists Meetup',
                'description' => 'An informal networking and mentorship event for pharmacy students across Kenyan universities. Features career talks from practising pharmacists and panel discussions.',
                'short_description' => 'Networking and mentorship event for pharmacy students.',
                'type' => EventType::Social,
                'status' => EventStatus::Draft,
                'venue_name' => 'University of Nairobi, School of Pharmacy',
                'venue_address' => 'University Way, Nairobi',
                'is_virtual' => false,
                'start_date' => '2026-08-22 10:00:00',
                'end_date' => '2026-08-22 16:00:00',
                'max_attendees' => 200,
                'cpd_points' => 0,
                'created_by' => $secretaryAdmin->id,
                'created_at' => '2026-03-01 10:00:00',
            ]);

            EventTicketType::create([
                'event_id' => $event6->id,
                'name' => 'Student Pass',
                'description' => 'Free for all registered pharmacy students.',
                'price' => 0.00,
                'quantity' => 200,
                'sold_count' => 0,
                'is_active' => true,
                'sort_order' => 1,
            ]);

            // -------------------------------------------------------
            // 7. Event Registrations (15)
            // -------------------------------------------------------
            $regSeq = 1;

            // 10 Confirmed registrations across upcoming events
            $confirmedRegs = [
                ['member' => 0, 'event' => $event1, 'ticket' => $ticket1a],
                ['member' => 1, 'event' => $event1, 'ticket' => $ticket1a],
                ['member' => 2, 'event' => $event1, 'ticket' => $ticket1a],
                ['member' => 3, 'event' => $event2, 'ticket' => $ticket2],
                ['member' => 4, 'event' => $event2, 'ticket' => $ticket2],
                ['member' => 5, 'event' => $event2, 'ticket' => $ticket2],
                ['member' => 6, 'event' => $event3, 'ticket' => $ticket3],
                ['member' => 7, 'event' => $event3, 'ticket' => $ticket3],
                ['member' => 8, 'event' => $event4, 'ticket' => $ticket4],
                ['member' => 9, 'event' => $event4, 'ticket' => $ticket4],
            ];

            foreach ($confirmedRegs as $reg) {
                EventRegistration::create([
                    'event_id' => $reg['event']->id,
                    'user_id' => $members[$reg['member']]->id,
                    'ticket_type_id' => $reg['ticket']->id,
                    'registration_number' => 'REG-2026-' . str_pad($regSeq++, 5, '0', STR_PAD_LEFT),
                    'status' => RegistrationStatus::Confirmed,
                    'amount_paid' => $reg['ticket']->price,
                    'qr_code_data' => 'QR-' . strtoupper(substr(md5($reg['event']->id . '-' . $members[$reg['member']]->id), 0, 12)),
                    'created_at' => '2026-03-0' . min($regSeq, 6) . ' 10:00:00',
                ]);
            }

            // 3 Pending registrations (for paid events)
            $pendingRegs = [
                ['member' => 10, 'event' => $event1, 'ticket' => $ticket1a],
                ['member' => 11, 'event' => $event3, 'ticket' => $ticket3],
                ['member' => 12, 'event' => $event4, 'ticket' => $ticket4],
            ];

            foreach ($pendingRegs as $reg) {
                EventRegistration::create([
                    'event_id' => $reg['event']->id,
                    'user_id' => $members[$reg['member']]->id,
                    'ticket_type_id' => $reg['ticket']->id,
                    'registration_number' => 'REG-2026-' . str_pad($regSeq++, 5, '0', STR_PAD_LEFT),
                    'status' => RegistrationStatus::Pending,
                    'amount_paid' => 0,
                    'created_at' => '2026-03-05 14:00:00',
                ]);
            }

            // 2 Attended (completed event)
            foreach ([13, 14] as $memberIdx) {
                EventRegistration::create([
                    'event_id' => $event5->id,
                    'user_id' => $members[$memberIdx]->id,
                    'ticket_type_id' => $ticket5->id,
                    'registration_number' => 'REG-2026-' . str_pad($regSeq++, 5, '0', STR_PAD_LEFT),
                    'status' => RegistrationStatus::Attended,
                    'amount_paid' => 3000.00,
                    'qr_code_data' => 'QR-' . strtoupper(substr(md5($event5->id . '-' . $members[$memberIdx]->id), 0, 12)),
                    'checked_in_at' => '2026-02-18 08:30:00',
                    'checked_in_by' => $admin->id,
                    'created_at' => '2026-01-15 10:00:00',
                ]);
            }

            // -------------------------------------------------------
            // 8. Invoices (12), Payments (8), Receipts (8)
            // -------------------------------------------------------
            $invoiceSeq = NumberSequence::where('type', 'invoice_number')->first();
            $receiptSeq = NumberSequence::where('type', 'receipt_number')->first();
            $paymentCounter = 1;

            // --- 8 Membership invoices ---
            // 5 Paid membership invoices
            for ($i = 0; $i < 5; $i++) {
                $member = $members[$i];
                $tierName = $tierAssignments[$i];
                $tier = $tiers[$tierName];
                $fee = $tier->annual_fee;
                $invNumber = $invoiceSeq->generateNext();
                $dueDate = '2026-02-' . str_pad(15 + $i, 2, '0', STR_PAD_LEFT);
                $paidDate = '2026-02-' . str_pad(10 + $i, 2, '0', STR_PAD_LEFT);

                $invoice = Invoice::create([
                    'invoice_number' => $invNumber,
                    'user_id' => $member->id,
                    'cost_center_id' => $ccHQ->id,
                    'type' => InvoiceType::Membership,
                    'status' => InvoiceStatus::Paid,
                    'subtotal' => $fee,
                    'tax_amount' => 0,
                    'discount_amount' => 0,
                    'total_amount' => $fee,
                    'amount_paid' => $fee,
                    'balance_due' => 0,
                    'currency' => 'KES',
                    'due_date' => $dueDate,
                    'paid_at' => $paidDate . ' 12:00:00',
                    'notes' => 'Annual membership fee - ' . $tierName . ' tier',
                    'created_at' => '2026-01-' . str_pad($i + 5, 2, '0', STR_PAD_LEFT) . ' 10:00:00',
                ]);

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => 'Annual Membership Fee - ' . $tierName . ' Tier (2026)',
                    'quantity' => 1,
                    'unit_price' => $fee,
                    'tax_rate' => 0,
                    'tax_amount' => 0,
                    'discount_amount' => 0,
                    'total' => $fee,
                ]);

                $paymentMethods = [PaymentMethod::Mpesa, PaymentMethod::BankTransfer, PaymentMethod::Mpesa, PaymentMethod::Card, PaymentMethod::Mpesa];
                $payment = Payment::create([
                    'invoice_id' => $invoice->id,
                    'payment_number' => 'PAY-2026-' . str_pad($paymentCounter++, 5, '0', STR_PAD_LEFT),
                    'amount' => $fee,
                    'payment_method' => $paymentMethods[$i],
                    'payment_reference' => $paymentMethods[$i] === PaymentMethod::Mpesa
                        ? 'MPESA' . strtoupper(substr(md5($invoice->id), 0, 10))
                        : 'REF' . strtoupper(substr(md5($invoice->id), 0, 8)),
                    'status' => PaymentStatus::Completed,
                    'paid_at' => $paidDate . ' 12:00:00',
                    'received_by' => $financeAdmin->id,
                    'created_at' => $paidDate . ' 12:00:00',
                ]);

                Receipt::create([
                    'receipt_number' => $receiptSeq->generateNext(),
                    'payment_id' => $payment->id,
                    'invoice_id' => $invoice->id,
                    'user_id' => $member->id,
                    'amount' => $fee,
                    'issued_date' => $paidDate,
                    'created_at' => $paidDate . ' 12:05:00',
                ]);
            }

            // 3 Sent (unpaid) membership invoices
            for ($i = 5; $i < 8; $i++) {
                $member = $members[$i];
                $tierName = $tierAssignments[$i];
                $tier = $tiers[$tierName];
                $fee = $tier->annual_fee;
                $invNumber = $invoiceSeq->generateNext();

                $invoice = Invoice::create([
                    'invoice_number' => $invNumber,
                    'user_id' => $member->id,
                    'cost_center_id' => $ccHQ->id,
                    'type' => InvoiceType::Membership,
                    'status' => InvoiceStatus::Sent,
                    'subtotal' => $fee,
                    'tax_amount' => 0,
                    'discount_amount' => 0,
                    'total_amount' => $fee,
                    'amount_paid' => 0,
                    'balance_due' => $fee,
                    'currency' => 'KES',
                    'due_date' => '2026-03-31',
                    'notes' => 'Annual membership fee - ' . $tierName . ' tier',
                    'created_at' => '2026-03-01 10:00:00',
                ]);

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => 'Annual Membership Fee - ' . $tierName . ' Tier (2026)',
                    'quantity' => 1,
                    'unit_price' => $fee,
                    'tax_rate' => 0,
                    'tax_amount' => 0,
                    'discount_amount' => 0,
                    'total' => $fee,
                ]);
            }

            // --- 3 Event invoices ---
            // 1 Paid event invoice
            $eventInvMember = $members[0];
            $eventFee = 5000.00;
            $invNumber = $invoiceSeq->generateNext();

            $eventInvoice = Invoice::create([
                'invoice_number' => $invNumber,
                'user_id' => $eventInvMember->id,
                'cost_center_id' => $ccConf->id,
                'type' => InvoiceType::Event,
                'status' => InvoiceStatus::Paid,
                'subtotal' => $eventFee,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'total_amount' => $eventFee,
                'amount_paid' => $eventFee,
                'balance_due' => 0,
                'currency' => 'KES',
                'due_date' => '2026-03-15',
                'paid_at' => '2026-03-03 14:00:00',
                'notes' => 'PSK Annual Scientific Conference 2026 - Early Bird Registration',
                'created_at' => '2026-03-01 10:00:00',
            ]);

            InvoiceItem::create([
                'invoice_id' => $eventInvoice->id,
                'description' => 'PSK Annual Scientific Conference 2026 - Early Bird Ticket',
                'quantity' => 1,
                'unit_price' => $eventFee,
                'tax_rate' => 0,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'total' => $eventFee,
            ]);

            $eventPayment = Payment::create([
                'invoice_id' => $eventInvoice->id,
                'payment_number' => 'PAY-2026-' . str_pad($paymentCounter++, 5, '0', STR_PAD_LEFT),
                'amount' => $eventFee,
                'payment_method' => PaymentMethod::Mpesa,
                'payment_reference' => 'MPESA' . strtoupper(substr(md5('event-' . $eventInvoice->id), 0, 10)),
                'status' => PaymentStatus::Completed,
                'paid_at' => '2026-03-03 14:00:00',
                'received_by' => $financeAdmin->id,
                'created_at' => '2026-03-03 14:00:00',
            ]);

            Receipt::create([
                'receipt_number' => $receiptSeq->generateNext(),
                'payment_id' => $eventPayment->id,
                'invoice_id' => $eventInvoice->id,
                'user_id' => $eventInvMember->id,
                'amount' => $eventFee,
                'issued_date' => '2026-03-03',
                'created_at' => '2026-03-03 14:05:00',
            ]);

            // 2 Sent event invoices
            foreach ([1, 2] as $idx) {
                $member = $members[$idx];
                $invNumber = $invoiceSeq->generateNext();

                $invoice = Invoice::create([
                    'invoice_number' => $invNumber,
                    'user_id' => $member->id,
                    'cost_center_id' => $ccConf->id,
                    'type' => InvoiceType::Event,
                    'status' => InvoiceStatus::Sent,
                    'subtotal' => 5000.00,
                    'tax_amount' => 0,
                    'discount_amount' => 0,
                    'total_amount' => 5000.00,
                    'amount_paid' => 0,
                    'balance_due' => 5000.00,
                    'currency' => 'KES',
                    'due_date' => '2026-03-20',
                    'notes' => 'PSK Annual Scientific Conference 2026 - Early Bird Registration',
                    'created_at' => '2026-03-04 10:00:00',
                ]);

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => 'PSK Annual Scientific Conference 2026 - Early Bird Ticket',
                    'quantity' => 1,
                    'unit_price' => 5000.00,
                    'tax_rate' => 0,
                    'tax_amount' => 0,
                    'discount_amount' => 0,
                    'total' => 5000.00,
                ]);
            }

            // 1 Overdue invoice
            $overdueMember = $members[9];
            $invNumber = $invoiceSeq->generateNext();
            $overdueFee = 5000.00;

            $overdueInvoice = Invoice::create([
                'invoice_number' => $invNumber,
                'user_id' => $overdueMember->id,
                'cost_center_id' => $ccHQ->id,
                'type' => InvoiceType::Membership,
                'status' => InvoiceStatus::Overdue,
                'subtotal' => $overdueFee,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'total_amount' => $overdueFee,
                'amount_paid' => 0,
                'balance_due' => $overdueFee,
                'currency' => 'KES',
                'due_date' => '2026-02-15',
                'notes' => 'Annual membership fee - Pharmacist tier. OVERDUE.',
                'created_at' => '2026-01-15 10:00:00',
            ]);

            InvoiceItem::create([
                'invoice_id' => $overdueInvoice->id,
                'description' => 'Annual Membership Fee - Pharmacist Tier (2026)',
                'quantity' => 1,
                'unit_price' => $overdueFee,
                'tax_rate' => 0,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'total' => $overdueFee,
            ]);

            // 2 more paid invoices for completed Community Pharmacy Summit
            foreach ([13, 14] as $memberIdx) {
                $member = $members[$memberIdx];
                $invNumber = $invoiceSeq->generateNext();
                $fee = 3000.00;

                $invoice = Invoice::create([
                    'invoice_number' => $invNumber,
                    'user_id' => $member->id,
                    'cost_center_id' => $ccConf->id,
                    'type' => InvoiceType::Event,
                    'status' => InvoiceStatus::Paid,
                    'subtotal' => $fee,
                    'tax_amount' => 0,
                    'discount_amount' => 0,
                    'total_amount' => $fee,
                    'amount_paid' => $fee,
                    'balance_due' => 0,
                    'currency' => 'KES',
                    'due_date' => '2026-02-15',
                    'paid_at' => '2026-02-01 10:00:00',
                    'notes' => 'Community Pharmacy Summit 2026',
                    'created_at' => '2026-01-10 10:00:00',
                ]);

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => 'Community Pharmacy Summit 2026 - Standard Ticket',
                    'quantity' => 1,
                    'unit_price' => $fee,
                    'tax_rate' => 0,
                    'tax_amount' => 0,
                    'discount_amount' => 0,
                    'total' => $fee,
                ]);

                $payMethod = $memberIdx === 13 ? PaymentMethod::Cash : PaymentMethod::BankTransfer;
                $payment = Payment::create([
                    'invoice_id' => $invoice->id,
                    'payment_number' => 'PAY-2026-' . str_pad($paymentCounter++, 5, '0', STR_PAD_LEFT),
                    'amount' => $fee,
                    'payment_method' => $payMethod,
                    'payment_reference' => $payMethod === PaymentMethod::Cash
                        ? 'CASH-' . str_pad($memberIdx, 4, '0', STR_PAD_LEFT)
                        : 'BT-' . strtoupper(substr(md5('summit-' . $member->id), 0, 8)),
                    'status' => PaymentStatus::Completed,
                    'paid_at' => '2026-02-01 10:00:00',
                    'received_by' => $financeAdmin->id,
                    'created_at' => '2026-02-01 10:00:00',
                ]);

                Receipt::create([
                    'receipt_number' => $receiptSeq->generateNext(),
                    'payment_id' => $payment->id,
                    'invoice_id' => $invoice->id,
                    'user_id' => $member->id,
                    'amount' => $fee,
                    'issued_date' => '2026-02-01',
                    'created_at' => '2026-02-01 10:05:00',
                ]);
            }

            // -------------------------------------------------------
            // 9. CPD Activities (25)
            // -------------------------------------------------------
            $cpdTitles = [
                'Attended PSK Regional Pharmacy Update',
                'Completed Online Pharmacovigilance Course',
                'Presented at Hospital Pharmacy Forum',
                'Published article on Antimicrobial Stewardship',
                'Participated in Diabetes Management Workshop',
                'Self-study: WHO Essential Medicines List Update',
                'Mentored pharmacy intern at Kenyatta National Hospital',
                'Community health screening outreach - Kibera',
                'PSK Scientific Committee meeting attendance',
                'Research: Medication adherence in HIV patients',
                'Attended Drug Interaction Webinar',
                'Completed Clinical Trials Good Practice Course',
                'Workshop on Pharmaceutical Supply Chain Management',
                'Lecture on Pharmacogenomics at UoN',
                'Attended PPB Regulatory Updates Seminar',
                'Self-study: Kenya National Drug Policy Review',
                'Community vaccination campaign participation',
                'Mentored final year pharmacy student',
                'Ethics Committee quarterly review meeting',
                'Research: Herbal medicine interactions study',
                'Attended Community Pharmacy Summit 2026',
                'Completed Palliative Care Pharmacy Course',
                'Workshop on Compounding Best Practices',
                'Presented poster at East Africa Pharmacy Conference',
                'Self-study: Updated Kenya Clinical Guidelines',
            ];

            $cpdDescriptions = [
                'Attended the regional pharmacy update covering new treatment guidelines and drug formulary changes.',
                'Successfully completed the accredited online course on adverse drug reaction reporting and signal detection.',
                'Delivered a 30-minute presentation on medication reconciliation practices in hospital settings.',
                'Co-authored peer-reviewed article published in the East African Medical Journal.',
                'Hands-on workshop covering insulin therapy, glucose monitoring, and patient counselling techniques.',
                'Documented review of the updated WHO Model List of Essential Medicines with clinical notes.',
                'Supervised and mentored a pharmacy intern over 3 months covering dispensing and clinical pharmacy.',
                'Provided free health screenings and medication counselling during community outreach event.',
                'Participated in quarterly committee meeting reviewing CPD policies and scientific programme planning.',
                'Contributed to multi-site research study on factors affecting medication adherence in HIV patients.',
                'Attended a 2-hour webinar on clinically significant drug-drug and drug-food interactions.',
                'Completed online certification in Good Clinical Practice for pharmaceutical clinical trials.',
                'Two-day workshop on pharmaceutical procurement, storage, and distribution management.',
                'Delivered a guest lecture to 4th year pharmacy students on pharmacogenomics applications.',
                'Half-day seminar on recent regulatory changes from the Pharmacy and Poisons Board.',
                'Self-directed study of the revised National Drug Policy with documented learning objectives.',
                'Participated in county-level COVID-19 and routine immunisation community campaign.',
                'Provided structured mentorship to a final year pharmacy student on community pharmacy practice.',
                'Attended and contributed to the quarterly ethics review session on research proposals.',
                'Principal investigator on a study examining interactions between traditional herbal remedies and ARVs.',
                'Full attendance at the two-day Community Pharmacy Summit with all plenary and breakout sessions.',
                'Completed accredited online course covering pharmaceutical aspects of palliative care.',
                'Practical workshop on non-sterile and sterile compounding techniques and quality assurance.',
                'Presented research poster on antimicrobial resistance patterns at the regional pharmacy conference.',
                'Documented review of updated Kenya clinical guidelines for primary care pharmacy services.',
            ];

            $cpdSources = [
                CpdActivitySource::Event, CpdActivitySource::External, CpdActivitySource::Manual,
                CpdActivitySource::Manual, CpdActivitySource::Event, CpdActivitySource::Manual,
                CpdActivitySource::Manual, CpdActivitySource::Manual, CpdActivitySource::Manual,
                CpdActivitySource::Manual, CpdActivitySource::External, CpdActivitySource::External,
                CpdActivitySource::Event, CpdActivitySource::Manual, CpdActivitySource::Event,
                CpdActivitySource::Manual, CpdActivitySource::Manual, CpdActivitySource::Manual,
                CpdActivitySource::Manual, CpdActivitySource::Manual, CpdActivitySource::Event,
                CpdActivitySource::External, CpdActivitySource::Event, CpdActivitySource::Manual,
                CpdActivitySource::Manual,
            ];

            $cpdCategoryIds = [1, 3, 5, 4, 2, 6, 7, 8, 9, 10, 3, 2, 2, 5, 1, 6, 8, 7, 9, 10, 1, 3, 2, 5, 6];
            $cpdPoints = [5, 3, 5, 8, 4, 2, 5, 3, 3, 7, 2, 4, 6, 5, 3, 2, 3, 5, 3, 8, 10, 4, 5, 5, 2];
            $cpdStatuses = [
                'approved', 'approved', 'approved', 'approved', 'approved',
                'approved', 'approved', 'pending', 'approved', 'pending',
                'approved', 'approved', 'approved', 'pending', 'approved',
                'pending', 'approved', 'approved', 'approved', 'pending',
                'approved', 'approved', 'pending', 'approved', 'pending',
            ];

            for ($i = 0; $i < 25; $i++) {
                $memberIdx = $i % 20;
                $month = ($i % 3) + 1;
                $day = (($i * 3) % 28) + 1;
                $activityDate = sprintf('2026-%02d-%02d', $month, $day);

                $cpdData = [
                    'user_id' => $members[$memberIdx]->id,
                    'cpd_category_id' => $cpdCategoryIds[$i],
                    'title' => $cpdTitles[$i],
                    'description' => $cpdDescriptions[$i],
                    'points' => $cpdPoints[$i],
                    'activity_date' => $activityDate,
                    'source' => $cpdSources[$i],
                    'period_year' => 2026,
                    'status' => $cpdStatuses[$i],
                    'created_at' => $activityDate . ' 10:00:00',
                ];

                if ($cpdStatuses[$i] === 'approved') {
                    $cpdData['approved_by'] = $admin->id;
                    $cpdData['approved_at'] = $activityDate . ' 16:00:00';
                }

                DB::table('cpd_activities')->insert(array_merge($cpdData, [
                    'updated_at' => $activityDate . ' 16:00:00',
                    'source' => $cpdSources[$i]->value,
                ]));
            }

            // -------------------------------------------------------
            // 10. Tickets (8)
            // -------------------------------------------------------
            $ticketSeq = NumberSequence::where('type', 'ticket_number')->first();

            // 4 Open
            $openTickets = [
                [
                    'member' => 10, 'cat' => 1, 'type' => TicketType::Inquiry, 'priority' => TicketPriority::Medium,
                    'subject' => 'Membership renewal process inquiry',
                    'description' => 'I would like to understand the process for renewing my membership for 2026. My current membership expires at the end of this month. What documents do I need to provide?',
                ],
                [
                    'member' => 11, 'cat' => 2, 'type' => TicketType::Support, 'priority' => TicketPriority::High,
                    'subject' => 'Invoice not received for annual subscription',
                    'description' => 'I have not received my invoice for the 2026 annual membership fee. I made a bank transfer on 15th February but have not received confirmation or a receipt.',
                ],
                [
                    'member' => 12, 'cat' => 5, 'type' => TicketType::Support, 'priority' => TicketPriority::Low,
                    'subject' => 'Unable to update profile photo',
                    'description' => 'When I try to upload a new profile photo through the member portal, I get an error message. I have tried with both JPEG and PNG formats under 2MB.',
                ],
                [
                    'member' => 13, 'cat' => 3, 'type' => TicketType::Inquiry, 'priority' => TicketPriority::Medium,
                    'subject' => 'Conference workshop session capacity',
                    'description' => 'I would like to know the capacity for the Drug Interaction Masterclass and whether there are still slots available. Can members register for multiple workshop sessions?',
                ],
            ];

            foreach ($openTickets as $idx => $t) {
                Ticket::create([
                    'ticket_number' => $ticketSeq->generateNext(),
                    'user_id' => $members[$t['member']]->id,
                    'category_id' => $t['cat'],
                    'type' => $t['type'],
                    'priority' => $t['priority'],
                    'status' => TicketStatus::Open,
                    'subject' => $t['subject'],
                    'description' => $t['description'],
                    'created_at' => '2026-03-0' . ($idx + 1) . ' 09:00:00',
                ]);
            }

            // 2 InProgress
            $inProgressTickets = [
                [
                    'member' => 0, 'cat' => 4, 'type' => TicketType::Support, 'priority' => TicketPriority::High,
                    'subject' => 'CPD points not reflecting after workshop attendance',
                    'description' => 'I attended the Community Pharmacy Summit in February but my CPD points have not been credited to my account. I have the attendance certificate as evidence.',
                ],
                [
                    'member' => 5, 'cat' => 6, 'type' => TicketType::Complaint, 'priority' => TicketPriority::Urgent,
                    'subject' => 'Incorrect membership tier displayed on certificate',
                    'description' => 'My membership certificate shows "Intern" tier but I was approved as a "Pharmacist" tier member. This is causing issues with my workplace verification. Please rectify urgently.',
                ],
            ];

            $inProgressTicketModels = [];
            foreach ($inProgressTickets as $idx => $t) {
                $ticket = Ticket::create([
                    'ticket_number' => $ticketSeq->generateNext(),
                    'user_id' => $members[$t['member']]->id,
                    'category_id' => $t['cat'],
                    'assigned_to' => $idx === 0 ? $secretaryAdmin->id : $admin->id,
                    'type' => $t['type'],
                    'priority' => $t['priority'],
                    'status' => TicketStatus::InProgress,
                    'subject' => $t['subject'],
                    'description' => $t['description'],
                    'created_at' => '2026-02-2' . ($idx + 5) . ' 09:00:00',
                ]);
                $inProgressTicketModels[] = $ticket;
            }

            // Add responses to in-progress tickets
            TicketResponse::create([
                'ticket_id' => $inProgressTicketModels[0]->id,
                'user_id' => $secretaryAdmin->id,
                'message' => 'Thank you for reaching out. I can see your registration for the Community Pharmacy Summit. Let me check with the CPD team about the points allocation. I will update you within 24 hours.',
                'is_internal' => false,
                'created_at' => '2026-02-26 14:00:00',
            ]);

            TicketResponse::create([
                'ticket_id' => $inProgressTicketModels[0]->id,
                'user_id' => $secretaryAdmin->id,
                'message' => 'Internal note: Checked with CPD admin - batch processing for February events is pending. Should be resolved by end of week.',
                'is_internal' => true,
                'created_at' => '2026-02-26 14:30:00',
            ]);

            TicketResponse::create([
                'ticket_id' => $inProgressTicketModels[1]->id,
                'user_id' => $admin->id,
                'message' => 'I apologise for the inconvenience. I have escalated this to our membership team for immediate correction. We will reissue your certificate with the correct "Pharmacist" tier designation.',
                'is_internal' => false,
                'created_at' => '2026-02-26 10:00:00',
            ]);

            // 1 Resolved
            $resolvedTicket = Ticket::create([
                'ticket_number' => $ticketSeq->generateNext(),
                'user_id' => $members[3]->id,
                'category_id' => 5,
                'assigned_to' => $secretaryAdmin->id,
                'type' => TicketType::Support,
                'priority' => TicketPriority::Medium,
                'status' => TicketStatus::Resolved,
                'subject' => 'Password reset not working',
                'description' => 'I am unable to reset my password. The reset email link appears to have expired by the time I click it. I have tried multiple times.',
                'resolved_at' => '2026-02-20 15:00:00',
                'created_at' => '2026-02-18 10:00:00',
            ]);

            TicketResponse::create([
                'ticket_id' => $resolvedTicket->id,
                'user_id' => $secretaryAdmin->id,
                'message' => 'I have manually reset your password and sent you a new login link via email. The reset token expiry has been extended. Please try again and let us know if the issue persists.',
                'is_internal' => false,
                'created_at' => '2026-02-19 09:00:00',
            ]);

            TicketResponse::create([
                'ticket_id' => $resolvedTicket->id,
                'user_id' => $members[3]->id,
                'message' => 'Thank you! The new link worked perfectly. I can now access my account. Appreciate the quick help.',
                'is_internal' => false,
                'created_at' => '2026-02-20 14:00:00',
            ]);

            // 1 Closed
            $closedTicket = Ticket::create([
                'ticket_number' => $ticketSeq->generateNext(),
                'user_id' => $members[7]->id,
                'category_id' => 6,
                'assigned_to' => $admin->id,
                'type' => TicketType::Suggestion,
                'priority' => TicketPriority::Low,
                'status' => TicketStatus::Closed,
                'subject' => 'Suggestion: Add M-Pesa payment integration',
                'description' => 'It would be very convenient if we could pay membership fees and event registrations directly via M-Pesa STK push from the member portal, rather than paying manually and waiting for confirmation.',
                'resolved_at' => '2026-02-10 12:00:00',
                'closed_at' => '2026-02-15 10:00:00',
                'created_at' => '2026-02-05 11:00:00',
            ]);

            TicketResponse::create([
                'ticket_id' => $closedTicket->id,
                'user_id' => $admin->id,
                'message' => 'Thank you for this excellent suggestion! We are pleased to inform you that M-Pesa STK push integration is already on our development roadmap for Q2 2026. We will notify all members once this feature goes live.',
                'is_internal' => false,
                'created_at' => '2026-02-07 09:00:00',
            ]);

            // -------------------------------------------------------
            // 11. Branches (4)
            // -------------------------------------------------------
            $nairobiBranch = Branch::create([
                'name' => 'Nairobi Branch',
                'code' => 'BR-NBI',
                'county' => 'Nairobi County',
                'region' => 'Central',
                'description' => 'The Nairobi branch serves pharmacists and pharmaceutical professionals in Nairobi County and the surrounding metropolitan area.',
                'is_active' => true,
                'cost_center_id' => $ccBranch->id,
                'created_at' => '2026-01-01 08:00:00',
            ]);

            $mombasaBranch = Branch::create([
                'name' => 'Mombasa Branch',
                'code' => 'BR-MSA',
                'county' => 'Mombasa County',
                'region' => 'Coast',
                'description' => 'The Mombasa branch covers the coastal region including Mombasa, Kilifi, Kwale, and Lamu counties.',
                'is_active' => true,
                'cost_center_id' => $ccBranch->id,
                'created_at' => '2026-01-01 08:00:00',
            ]);

            $kisumuBranch = Branch::create([
                'name' => 'Kisumu Branch',
                'code' => 'BR-KSM',
                'county' => 'Kisumu County',
                'region' => 'Western',
                'description' => 'The Kisumu branch serves pharmacists in the Lake Region including Kisumu, Siaya, Homa Bay, and Migori counties.',
                'is_active' => true,
                'cost_center_id' => $ccBranch->id,
                'created_at' => '2026-01-01 08:00:00',
            ]);

            $nakuruBranch = Branch::create([
                'name' => 'Nakuru Branch',
                'code' => 'BR-NKR',
                'county' => 'Nakuru County',
                'region' => 'Rift Valley',
                'description' => 'The Nakuru branch covers the Rift Valley region including Nakuru, Baringo, Kericho, and Nandi counties.',
                'is_active' => true,
                'cost_center_id' => $ccBranch->id,
                'created_at' => '2026-01-01 08:00:00',
            ]);

            // Assign members to branches (3-5 per branch)
            $branchAssignments = [
                $nairobiBranch->id => [0, 1, 2, 3, 4],
                $mombasaBranch->id => [5, 6, 7],
                $kisumuBranch->id => [8, 9, 10, 11],
                $nakuruBranch->id => [12, 13, 14],
            ];

            foreach ($branchAssignments as $branchId => $memberIndices) {
                foreach ($memberIndices as $idx => $memberIdx) {
                    $role = $idx === 0 ? 'chair' : ($idx === 1 ? 'secretary' : 'member');
                    DB::table('branch_members')->insert([
                        'branch_id' => $branchId,
                        'user_id' => $members[$memberIdx]->id,
                        'role' => $role,
                        'joined_at' => '2026-01-15',
                        'is_active' => true,
                        'created_at' => '2026-01-15 10:00:00',
                        'updated_at' => '2026-01-15 10:00:00',
                    ]);
                }
            }

            // -------------------------------------------------------
            // 12. Committees (3)
            // -------------------------------------------------------
            $scientificCommittee = Committee::create([
                'name' => 'Scientific Committee',
                'code' => 'COM-SCI',
                'type' => 'standing',
                'description' => 'Responsible for overseeing the scientific programme of PSK events, reviewing CPD content, and advising on continuing professional development standards.',
                'is_active' => true,
                'created_at' => '2026-01-01 08:00:00',
            ]);

            $ethicsCommittee = Committee::create([
                'name' => 'Ethics Committee',
                'code' => 'COM-ETH',
                'type' => 'standing',
                'description' => 'Oversees ethical standards in pharmacy practice, reviews complaints, and advises on professional conduct matters within the society.',
                'is_active' => true,
                'created_at' => '2026-01-01 08:00:00',
            ]);

            $confCommittee = Committee::create([
                'name' => 'Conference Organizing Committee',
                'code' => 'COM-COC',
                'type' => 'ad_hoc',
                'description' => 'Temporary committee responsible for planning and executing the PSK Annual Scientific Conference 2026.',
                'cost_center_id' => $ccConf->id,
                'is_active' => true,
                'created_at' => '2026-01-15 08:00:00',
            ]);

            // Assign members to committees (3-4 per committee)
            $committeeAssignments = [
                $scientificCommittee->id => [3, 5, 7, 11],
                $ethicsCommittee->id => [0, 4, 9],
                $confCommittee->id => [1, 2, 6, 8],
            ];

            foreach ($committeeAssignments as $committeeId => $memberIndices) {
                foreach ($memberIndices as $idx => $memberIdx) {
                    $role = $idx === 0 ? 'chair' : ($idx === 1 ? 'secretary' : 'member');
                    DB::table('committee_members')->insert([
                        'committee_id' => $committeeId,
                        'user_id' => $members[$memberIdx]->id,
                        'role' => $role,
                        'appointed_at' => '2026-01-20',
                        'term_ends_at' => '2027-12-31',
                        'is_active' => true,
                        'created_at' => '2026-01-20 10:00:00',
                        'updated_at' => '2026-01-20 10:00:00',
                    ]);
                }
            }

            // -------------------------------------------------------
            // 13. Posts (5) and Comments
            // -------------------------------------------------------
            $post1 = Post::create([
                'user_id' => $admin->id,
                'title' => 'Welcome to the PSK Member Portal 2026',
                'body' => "Dear PSK Members,\n\nWe are pleased to welcome you to the newly redesigned PSK Member Portal for 2026. This platform has been built from the ground up to better serve your professional needs.\n\nKey features include:\n- Online membership management and renewal\n- Event registration and CPD tracking\n- Digital invoicing and payment processing\n- Community discussion forums\n- Branch and committee management\n\nPlease explore the portal and do not hesitate to reach out to us through the support ticket system if you encounter any issues.\n\nWarm regards,\nThe PSK Secretariat",
                'type' => 'announcement',
                'status' => PostStatus::Published,
                'is_pinned' => true,
                'published_at' => '2026-01-05 08:00:00',
                'created_at' => '2026-01-05 08:00:00',
            ]);

            PostComment::create([
                'post_id' => $post1->id,
                'user_id' => $members[0]->id,
                'body' => 'This is wonderful! The new portal is much easier to navigate. Thank you for the improvement.',
                'created_at' => '2026-01-05 12:00:00',
            ]);

            PostComment::create([
                'post_id' => $post1->id,
                'user_id' => $members[3]->id,
                'body' => 'Great initiative! I especially appreciate the CPD tracking feature. Will there be a mobile app version as well?',
                'created_at' => '2026-01-06 09:00:00',
            ]);

            PostComment::create([
                'post_id' => $post1->id,
                'user_id' => $admin->id,
                'body' => 'Thank you for the positive feedback! Yes, a mobile app is on our roadmap for later this year.',
                'created_at' => '2026-01-06 14:00:00',
            ]);

            $post2 = Post::create([
                'user_id' => $secretaryAdmin->id,
                'title' => 'PSK Annual Scientific Conference 2026 - Call for Abstracts',
                'body' => "The PSK is pleased to announce the call for abstracts for the Annual Scientific Conference 2026.\n\nTheme: \"Advancing Pharmaceutical Care in the Digital Age\"\nDate: 15-17 July 2026\nVenue: Kenyatta International Convention Centre, Nairobi\n\nWe invite submissions in the following categories:\n- Clinical Pharmacy and Therapeutics\n- Pharmaceutical Technology and Innovation\n- Public Health Pharmacy\n- Pharmacy Education and Training\n- Regulatory and Policy\n\nAbstract submission deadline: 30 April 2026\n\nPlease submit your abstracts through the conference portal.",
                'type' => 'announcement',
                'status' => PostStatus::Published,
                'is_pinned' => false,
                'published_at' => '2026-02-15 10:00:00',
                'created_at' => '2026-02-15 10:00:00',
            ]);

            PostComment::create([
                'post_id' => $post2->id,
                'user_id' => $members[5]->id,
                'body' => 'This is exciting! I have a research poster on antimicrobial stewardship that I would like to present. What is the recommended poster size?',
                'created_at' => '2026-02-16 11:00:00',
            ]);

            PostComment::create([
                'post_id' => $post2->id,
                'user_id' => $secretaryAdmin->id,
                'body' => 'Posters should be A0 size (841 x 1189 mm) in portrait orientation. Detailed guidelines will be shared with accepted authors.',
                'created_at' => '2026-02-16 15:00:00',
            ]);

            $post3 = Post::create([
                'user_id' => $admin->id,
                'title' => 'Important: Membership Renewal Deadline Approaching',
                'body' => "Dear Members,\n\nThis is a reminder that the deadline for 2026 membership renewal is 31 March 2026. Members who have not renewed by this date will have their membership status changed to expired.\n\nTo renew your membership:\n1. Log in to the member portal\n2. Navigate to My Membership\n3. Click Renew Membership\n4. Complete the payment via M-Pesa or bank transfer\n\nIf you have any questions about the renewal process, please submit a support ticket.\n\nThank you,\nPSK Membership Team",
                'type' => 'announcement',
                'status' => PostStatus::Published,
                'is_pinned' => false,
                'published_at' => '2026-03-01 08:00:00',
                'created_at' => '2026-03-01 08:00:00',
            ]);

            $post4 = Post::create([
                'user_id' => $members[2]->id,
                'title' => 'Discussion: Best Practices for Pharmaceutical Waste Management',
                'body' => "Fellow pharmacists,\n\nI would like to open a discussion on pharmaceutical waste management practices in Kenya. With the increasing focus on environmental sustainability, how are your pharmacies handling:\n\n1. Expired medication disposal\n2. Cytotoxic waste handling\n3. Sharps disposal\n4. Environmental compliance documentation\n\nI recently attended a workshop on this topic and learned about some new NEMA guidelines that affect community pharmacies. Would love to hear your experiences and any challenges you are facing.\n\nLooking forward to the discussion!",
                'type' => 'discussion',
                'status' => PostStatus::Published,
                'is_pinned' => false,
                'published_at' => '2026-02-20 14:00:00',
                'created_at' => '2026-02-20 14:00:00',
            ]);

            PostComment::create([
                'post_id' => $post4->id,
                'user_id' => $members[6]->id,
                'body' => 'Great topic! At our hospital pharmacy, we have partnered with a licensed waste handler. The main challenge is the cost, which is quite high for smaller facilities.',
                'created_at' => '2026-02-21 09:00:00',
            ]);

            PostComment::create([
                'post_id' => $post4->id,
                'user_id' => $members[9]->id,
                'body' => 'We follow the NEMA guidelines but I agree there needs to be more clarity on disposal of partially used vials. Would PSK consider developing a practice guideline on this?',
                'created_at' => '2026-02-22 10:00:00',
            ]);

            $post5 = Post::create([
                'user_id' => $members[4]->id,
                'title' => 'Seeking Recommendations: Pharmacy Management Software',
                'body' => "Hello everyone,\n\nI am looking to upgrade the dispensing and stock management system at my community pharmacy. Currently using manual records and Excel spreadsheets.\n\nCan anyone recommend a good pharmacy management software that:\n- Works well in the Kenyan market\n- Supports eTIMS integration\n- Has good inventory management\n- Offers point-of-sale features\n- Is reasonably priced for a single pharmacy\n\nAny recommendations or experiences would be greatly appreciated!",
                'type' => 'discussion',
                'status' => PostStatus::Published,
                'is_pinned' => false,
                'published_at' => '2026-03-02 16:00:00',
                'created_at' => '2026-03-02 16:00:00',
            ]);

            PostComment::create([
                'post_id' => $post5->id,
                'user_id' => $members[7]->id,
                'body' => 'We have been using a locally developed system and it handles the eTIMS integration quite well. Happy to share more details via direct message.',
                'created_at' => '2026-03-03 08:00:00',
            ]);

            // -------------------------------------------------------
            // 14. Communications (3)
            // -------------------------------------------------------
            $comm1 = Communication::create([
                'type' => CommunicationType::Email,
                'channel' => 'email',
                'subject' => 'Welcome to 2026 - PSK New Year Message',
                'body' => "Dear PSK Member,\n\nHappy New Year 2026!\n\nOn behalf of the PSK Council, I wish you a prosperous and fulfilling year ahead. We have an exciting programme lined up for 2026 including our flagship Annual Scientific Conference, regional workshops, and new digital services.\n\nKey dates for your diary:\n- March 31: Membership renewal deadline\n- April 20: Pharmacy Practice Workshop (Virtual)\n- July 15-17: Annual Scientific Conference, KICC Nairobi\n\nThank you for your continued support of the pharmaceutical profession in Kenya.\n\nDr. Sarah Kimani\nPSK President",
                'status' => 'sent',
                'sent_by' => $admin->id,
                'sent_at' => '2026-01-03 09:00:00',
                'created_at' => '2026-01-02 16:00:00',
            ]);

            // Add recipients for comm1
            foreach (array_slice($members, 0, 15) as $member) {
                CommunicationRecipient::create([
                    'communication_id' => $comm1->id,
                    'user_id' => $member->id,
                    'status' => 'delivered',
                    'sent_at' => '2026-01-03 09:00:00',
                    'delivered_at' => '2026-01-03 09:01:00',
                    'created_at' => '2026-01-03 09:00:00',
                ]);
            }

            $comm2 = Communication::create([
                'type' => CommunicationType::Email,
                'channel' => 'email',
                'subject' => 'Membership Renewal Reminder - Action Required',
                'body' => "Dear Member,\n\nThis is a friendly reminder that your PSK membership renewal for 2026 is due by 31 March 2026.\n\nTo avoid any interruption to your membership benefits, please:\n1. Log in to your member portal\n2. Review and update your profile information\n3. Complete your membership renewal payment\n\nIf you have already renewed, please disregard this message.\n\nFor any questions, please contact the PSK Secretariat or submit a support ticket through the portal.\n\nBest regards,\nPSK Membership Team",
                'status' => 'sent',
                'sent_by' => $admin->id,
                'sent_at' => '2026-02-15 10:00:00',
                'created_at' => '2026-02-14 15:00:00',
            ]);

            foreach (array_slice($members, 0, 20) as $member) {
                CommunicationRecipient::create([
                    'communication_id' => $comm2->id,
                    'user_id' => $member->id,
                    'status' => 'delivered',
                    'sent_at' => '2026-02-15 10:00:00',
                    'delivered_at' => '2026-02-15 10:02:00',
                    'created_at' => '2026-02-15 10:00:00',
                ]);
            }

            $comm3 = Communication::create([
                'type' => CommunicationType::Email,
                'channel' => 'email',
                'subject' => 'PSK Annual Scientific Conference 2026 - Registration Now Open',
                'body' => "Dear PSK Member,\n\nWe are delighted to announce that registration is now open for the PSK Annual Scientific Conference 2026!\n\nTheme: \"Advancing Pharmaceutical Care in the Digital Age\"\nDate: 15-17 July 2026\nVenue: Kenyatta International Convention Centre, Nairobi\n\nEarly Bird Rate: KES 5,000 (until 31 May 2026)\nRegular Rate: KES 7,500\n\nHighlights:\n- 15 CPD points\n- Keynote speakers from across East Africa\n- Interactive workshops and breakout sessions\n- Exhibition by pharmaceutical companies\n- Networking dinner\n\nRegister now through the events section of your member portal.\n\nWe look forward to seeing you in July!\n\nConference Organizing Committee",
                'status' => 'sent',
                'sent_by' => $secretaryAdmin->id,
                'sent_at' => '2026-03-01 08:00:00',
                'created_at' => '2026-02-28 14:00:00',
            ]);

            foreach (array_slice($members, 0, 20) as $member) {
                CommunicationRecipient::create([
                    'communication_id' => $comm3->id,
                    'user_id' => $member->id,
                    'status' => 'delivered',
                    'sent_at' => '2026-03-01 08:00:00',
                    'delivered_at' => '2026-03-01 08:03:00',
                    'created_at' => '2026-03-01 08:00:00',
                ]);
            }

            // -------------------------------------------------------
            // 15. Budgets (2)
            // -------------------------------------------------------
            $budgetHQ = Budget::create([
                'cost_center_id' => $ccHQ->id,
                'fiscal_year' => 2026,
                'name' => 'Headquarters Operations FY 2025/2026',
                'total_amount' => 8500000.00,
                'status' => 'approved',
                'approved_by' => $admin->id,
                'approved_at' => '2025-12-15 10:00:00',
                'notes' => 'Annual operating budget for PSK headquarters covering staff, rent, utilities, and administrative expenses.',
                'created_at' => '2025-12-01 10:00:00',
            ]);

            BudgetLine::create([
                'budget_id' => $budgetHQ->id,
                'category' => 'Staff Salaries & Benefits',
                'description' => 'Salaries, NSSF, NHIF, and other staff benefits for headquarters personnel.',
                'budgeted_amount' => 4800000.00,
                'actual_amount' => 1200000.00,
                'variance' => -3600000.00,
            ]);

            BudgetLine::create([
                'budget_id' => $budgetHQ->id,
                'category' => 'Office Rent & Utilities',
                'description' => 'Monthly rent, electricity, water, internet, and telephone for headquarters office.',
                'budgeted_amount' => 1800000.00,
                'actual_amount' => 450000.00,
                'variance' => -1350000.00,
            ]);

            BudgetLine::create([
                'budget_id' => $budgetHQ->id,
                'category' => 'Administrative Expenses',
                'description' => 'Office supplies, printing, postage, travel, and miscellaneous administrative costs.',
                'budgeted_amount' => 1200000.00,
                'actual_amount' => 280000.00,
                'variance' => -920000.00,
            ]);

            BudgetLine::create([
                'budget_id' => $budgetHQ->id,
                'category' => 'IT & Digital Services',
                'description' => 'Software licences, hosting, member portal maintenance, and IT equipment.',
                'budgeted_amount' => 700000.00,
                'actual_amount' => 175000.00,
                'variance' => -525000.00,
            ]);

            $budgetConf = Budget::create([
                'cost_center_id' => $ccConf->id,
                'fiscal_year' => 2026,
                'name' => 'Annual Scientific Conference 2026',
                'total_amount' => 5000000.00,
                'status' => 'approved',
                'approved_by' => $admin->id,
                'approved_at' => '2026-01-20 10:00:00',
                'notes' => 'Budget for the PSK Annual Scientific Conference 2026 at KICC, covering venue, speakers, catering, and logistics.',
                'created_at' => '2026-01-10 10:00:00',
            ]);

            BudgetLine::create([
                'budget_id' => $budgetConf->id,
                'category' => 'Venue Hire',
                'description' => 'KICC main hall and breakout rooms for 3 days including AV equipment.',
                'budgeted_amount' => 1500000.00,
                'actual_amount' => 0,
                'variance' => -1500000.00,
            ]);

            BudgetLine::create([
                'budget_id' => $budgetConf->id,
                'category' => 'Catering',
                'description' => 'Meals, tea breaks, and networking dinner for 500 delegates over 3 days.',
                'budgeted_amount' => 1800000.00,
                'actual_amount' => 0,
                'variance' => -1800000.00,
            ]);

            BudgetLine::create([
                'budget_id' => $budgetConf->id,
                'category' => 'Speaker Fees & Travel',
                'description' => 'Honoraria, flights, accommodation, and transport for invited speakers.',
                'budgeted_amount' => 1000000.00,
                'actual_amount' => 0,
                'variance' => -1000000.00,
            ]);

            BudgetLine::create([
                'budget_id' => $budgetConf->id,
                'category' => 'Marketing & Materials',
                'description' => 'Conference branding, delegate packs, printed materials, and promotional activities.',
                'budgeted_amount' => 700000.00,
                'actual_amount' => 150000.00,
                'variance' => -550000.00,
            ]);
        });
    }
}
