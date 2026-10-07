import type { ReactNode } from 'react';
import WorkspaceLayout from '@/layouts/workspace-layout';
import { investisseurNav } from '@/config/navigation';

export default function InvestisseurLayout({ children }: { children: ReactNode }) {
  return <WorkspaceLayout config={investisseurNav}>{children}</WorkspaceLayout>;
}
